<?php
ob_start();
?>

<!-- Careers -->
<?php

$pMmsg4 = '';

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {
    /*
     * Cloudflare Turnstile verification.
     */
    $turnstileSecretKey = '0x4AAAAAAC-YdW99Q27bvAi4AzHnY8VJTNM';

    $turnstileToken = trim(
        $_POST['cf-turnstile-response'] ?? ''
    );

    if ($turnstileToken === '') {
        $pMmsg4 = $dbF->hardWords(
            'Please complete the security verification.',
            false
        );
    } else {
        $verifyData = [
            'secret'   => $turnstileSecretKey,
            'response' => $turnstileToken
        ];

        if (!empty($_SERVER['REMOTE_ADDR'])) {
            $verifyData['remoteip'] = $_SERVER['REMOTE_ADDR'];
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL =>
                'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($verifyData),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/x-www-form-urlencoded'
            ]
        ]);

        $verifyResponse = curl_exec($curl);
        $curlError = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        $turnstileResult = json_decode($verifyResponse, true);

        $turnstileVerified =
            $curlError === '' &&
            $httpCode === 200 &&
            is_array($turnstileResult) &&
            !empty($turnstileResult['success']) &&
            isset($turnstileResult['action']) &&
            hash_equals(
                'career_form',
                (string) $turnstileResult['action']
            );

        if (!$turnstileVerified) {
            $pMmsg4 = $dbF->hardWords(
                'Security verification failed. Please refresh the page and try again.',
                false
            );
        } elseif (
            !isset($_POST['form']) ||
            !is_array($_POST['form'])
        ) {
            $pMmsg4 = $dbF->hardWords(
                'Invalid form submission.',
                false
            );
        } else {
            /*
             * Form fields.
             */
            $nameUser = trim($_POST['form']['name'] ?? '');
            $emailUser = trim($_POST['form']['email'] ?? '');
            $phoneUser = trim($_POST['form']['phone'] ?? '');
            $positionUser = trim($_POST['form']['position'] ?? '');
            $experienceUser = trim($_POST['form']['experience'] ?? '');
            $messageUser = trim($_POST['form']['message'] ?? '');

            /*
             * Form validation.
             */
            if (
                $nameUser === '' ||
                $emailUser === '' ||
                $phoneUser === '' ||
                $positionUser === '' ||
                $experienceUser === ''
            ) {
                $pMmsg4 = $dbF->hardWords(
                    'Please fill in all required fields.',
                    false
                );
            } elseif (
                !filter_var($emailUser, FILTER_VALIDATE_EMAIL)
            ) {
                $pMmsg4 = $dbF->hardWords(
                    'Please enter a valid email address.',
                    false
                );
            } elseif (
                !isset($_FILES['resume']) ||
                !is_array($_FILES['resume'])
            ) {
                $pMmsg4 = $dbF->hardWords(
                    'Please upload your resume.',
                    false
                );
            } elseif (
                $_FILES['resume']['error'] !== UPLOAD_ERR_OK
            ) {
                $pMmsg4 = $dbF->hardWords(
                    'Resume upload failed. Please upload the file again.',
                    false
                );
            } else {
                /*
                 * Resume upload validation.
                 */
                $resumeFile = $_FILES['resume'];
                $originalFileName = basename($resumeFile['name']);
                $temporaryFile = $resumeFile['tmp_name'];
                $fileSize = (int) $resumeFile['size'];

                $fileExtension = strtolower(
                    pathinfo(
                        $originalFileName,
                        PATHINFO_EXTENSION
                    )
                );

                $allowedExtensions = [
                    'pdf',
                    'doc',
                    'docx'
                ];

                $maximumFileSize = 5 * 1024 * 1024;

                if (
                    !in_array(
                        $fileExtension,
                        $allowedExtensions,
                        true
                    )
                ) {
                    $pMmsg4 = $dbF->hardWords(
                        'Only PDF, DOC and DOCX files are allowed.',
                        false
                    );
                } elseif ($fileSize <= 0) {
                    $pMmsg4 = $dbF->hardWords(
                        'The uploaded resume is empty.',
                        false
                    );
                } elseif ($fileSize > $maximumFileSize) {
                    $pMmsg4 = $dbF->hardWords(
                        'Resume size must not exceed 5 MB.',
                        false
                    );
                } elseif (!is_uploaded_file($temporaryFile)) {
                    $pMmsg4 = $dbF->hardWords(
                        'Invalid resume upload.',
                        false
                    );
                } else {
                    /*
                     * Check actual MIME type.
                     */
                    $fileInfo = new finfo(FILEINFO_MIME_TYPE);
                    $mimeType = $fileInfo->file($temporaryFile);

                    $allowedMimeTypes = [
                        'pdf' => [
                            'application/pdf'
                        ],
                        'doc' => [
                            'application/msword',
                            'application/CDFV2',
                            'application/octet-stream'
                        ],
                        'docx' => [
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/zip',
                            'application/octet-stream'
                        ]
                    ];

                    if (
                        !isset($allowedMimeTypes[$fileExtension]) ||
                        !in_array(
                            $mimeType,
                            $allowedMimeTypes[$fileExtension],
                            true
                        )
                    ) {
                        $pMmsg4 = $dbF->hardWords(
                            'The uploaded resume file is invalid.',
                            false
                        );
                    } else {
                        /*
                         * Upload folder:
                         * public_html/uploads/careers/
                         */
                        $uploadDirectory =
                            __DIR__ . '/uploads/careers/';

                        if (
                            !is_dir($uploadDirectory) &&
                            !mkdir(
                                $uploadDirectory,
                                0755,
                                true
                            )
                        ) {
                            $pMmsg4 = $dbF->hardWords(
                                'The upload folder could not be created.',
                                false
                            );
                        } else {
                            /*
                             * Create a safe unique filename.
                             */
                            try {
                                $randomName = bin2hex(
                                    random_bytes(12)
                                );
                            } catch (Exception $exception) {
                                $randomName = uniqid('', true);
                                $randomName = str_replace(
                                    '.',
                                    '',
                                    $randomName
                                );
                            }

                            $storedFileName =
                                date('YmdHis') .
                                '-' .
                                $randomName .
                                '.' .
                                $fileExtension;

                            $destinationPath =
                                $uploadDirectory .
                                $storedFileName;

                            if (
                                !move_uploaded_file(
                                    $temporaryFile,
                                    $destinationPath
                                )
                            ) {
                                $pMmsg4 = $dbF->hardWords(
                                    'Resume could not be uploaded.',
                                    false
                                );
                            } else {
                                /*
                                 * Create public resume URL.
                                 */
                                $resumeUrl =
                                    rtrim(WEB_URL, '/') .
                                    '/uploads/careers/' .
                                    rawurlencode($storedFileName);

                                $formData = [
                                    'Full Name' =>
                                        $nameUser,

                                    'Email Address' =>
                                        $emailUser,

                                    'Phone Number' =>
                                        $phoneUser,

                                    'Position Applied For' =>
                                        $positionUser,

                                    'Total Experience' =>
                                        $experienceUser,

                                    'Cover Letter / Message' =>
                                        $messageUser,

                                    'Resume Original Name' =>
                                        $originalFileName,

                                    'Resume URL' =>
                                        $resumeUrl
                                ];

                                $databaseFields = [];
                                $databaseValues = [];
                                $fieldCounter = 1;

                                $mailMessage = '
                                    <table
                                        border="1"
                                        cellpadding="8"
                                        cellspacing="0"
                                        style="
                                            border-collapse:collapse;
                                            width:100%;
                                            font-family:Arial,sans-serif;
                                        "
                                    >
                                ';

                                foreach (
                                    $formData as $fieldTitle => $value
                                ) {
                                    $databaseFields[] =
                                        'field' .
                                        $fieldCounter .
                                        ' = ?';

                                    $databaseValues[] =
                                        $fieldTitle .
                                        ':' .
                                        $value;

                                    $displayValue = htmlspecialchars(
                                        $value,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );

                                    if ($fieldTitle === 'Resume URL') {
                                        $displayValue =
                                            '<a href="' .
                                            htmlspecialchars(
                                                $resumeUrl,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) .
                                            '" target="_blank">
                                                Download Resume
                                            </a>';
                                    } else {
                                        $displayValue = nl2br(
                                            $displayValue
                                        );
                                    }

                                    $mailMessage .= '
                                        <tr>
                                            <td
                                                style="
                                                    width:30%;
                                                    font-weight:bold;
                                                    vertical-align:top;
                                                "
                                            >
                                                ' .
                                                htmlspecialchars(
                                                    $fieldTitle,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) .
                                                '
                                            </td>

                                            <td>
                                                ' .
                                                $displayValue .
                                                '
                                            </td>
                                        </tr>
                                    ';

                                    $fieldCounter++;
                                }

                                $mailMessage .= '
                                    <tr>
                                        <td style="font-weight:bold;">
                                            Submission Date
                                        </td>

                                        <td>
                                            ' .
                                            date('D j M Y g:i a') .
                                            '
                                        </td>
                                    </tr>
                                ';

                                $mailMessage .= '</table>';

                                /*
                                 * Save application in database.
                                 */
                                $databaseFields[] = 'type = ?';
                                $databaseValues[] =
                                    'Career Application';

                                $sql = '
                                    INSERT INTO `surveyFormData`
                                    SET ' .
                                    implode(
                                        ', ',
                                        $databaseFields
                                    );

                                $dbF->setRow(
                                    $sql,
                                    $databaseValues,
                                    false
                                );

                                /*
                                 * Send email to administrator.
                                 */
                                $adminEmail =
                                    $functions->ibms_setting(
                                        'Email'
                                    );
                                // $adminEmail = "sburhanali13@gmail.com";  

                                $functions->send_mail(
                                    $adminEmail,
                                    'New Career Application - ' .
                                    $positionUser,
                                    $mailMessage
                                );

                                /*
                                 * Send confirmation to applicant.
                                 */
                                $userMessage = '
                                    <p>Dear ' .
                                    htmlspecialchars(
                                        $nameUser,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) .
                                    ',</p>

                                    <p>
                                        Thank you for applying for the
                                        <strong>' .
                                        htmlspecialchars(
                                            $positionUser,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) .
                                        '</strong> position.
                                    </p>

                                    <p>
                                        We have received your application
                                        and resume. Our HR team will contact
                                        you if your profile is shortlisted.
                                    </p>
                                ';

                                $functions->send_mail(
                                    $emailUser,
                                    'Your Application Has Been Received',
                                    $userMessage
                                );

                                $pMmsg4 = $dbF->hardWords(
                                    'Your application has been submitted successfully. Our HR team will contact you.',
                                    false
                                );

                                $_POST['form'] = [];
                            }
                        }
                    }
                }
            }
        }
    }
}
?>
<div class="industify_fn_contact">
    <div class="container">
        <div class="contact_in">

            <div class="contact_holder">
                <div class="contact_left ce_full_width_form">
                    <?php
                    if (!empty($pMmsg4)) {
                        echo "<div style='text-align: center;
                        padding: 20px; margin-bottom: 20px; border: 1px solid transparent;
                        border-radius: 4px; background-color: #d9edf7; border-color: #bce8f1;'>
                        <p class='submit_msg' >" . $pMmsg4 . "</p></div>";
                    }
                    ?>
                    <h3>Apply for a Position</h3>
                    <form class="contact_form" method="POST" autocomplete="off" enctype="multipart/form-data">
                        
                        <div class="success" data-success="Your application has been submitted successfully. Our HR team will contact you."></div>
                        <div class="empty_notice"><span>Please Fill Required Fields</span></div>

                        <div class="items careers_form_items">
                            <div class="item">
                                <input id="career_name" name="form[name]" type="text" placeholder="Full Name *" required />
                            </div>
                            <div class="item">
                                <input id="career_email" name="form[email]" type="email" placeholder="Email Address *" required />
                            </div>
                            <div class="item">
                                <input id="career_phone" name="form[phone]" type="tel" placeholder="Phone Number *" required />
                            </div>
                            <div class="item">
                                <input id="career_position" name="form[position]" type="text" placeholder="Position Applied For *" required />
                            </div>
                            <div class="item">
                                <input id="career_experience" name="form[experience]" type="text" placeholder="Total Experience (e.g. 3 Years) *" required />
                            </div>
                            <div class="item">
                                <input id="career_resume" name="resume"  accept=".pdf,.doc,.docx" required type="file" accept=".pdf,.doc,.docx" required />
                                 <small>
                                    PDF, DOC or DOCX only. Maximum size: 5 MB.
                                </small>
                            </div>
                            <div class="item full_width">
                                <textarea id="career_message" name="form[message]" placeholder="Cover Letter / Message"></textarea>
                            </div>
                            <div class="item full_width">
                                <div
                                    class="cf-turnstile"
                                    data-sitekey="0x4AAAAAAC-YdURxteUuxXmh"
                                    data-action="career_form"
                                ></div>
                            </div>
                            <div class="item full_width">
                                <button id="submit_application" type="submit" value="1" class="ce_career_submit_btn">Submit Application</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
return ob_get_clean();
?>