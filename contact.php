<?php
ob_start();
$Email =  $functions->ibms_setting('Email');
$contact =  $functions->ibms_setting('contact');
$address =  $functions->ibms_setting('address');
?>
 <?php

$pMmsg4 = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $turnstileSecretKey = '0x4AAAAAAC-YdW99Q27bvAi4AzHnY8VJTNM';
    $turnstileToken = isset($_POST['cf-turnstile-response'])
        ? trim($_POST['cf-turnstile-response'])
        : '';
    if (empty($turnstileToken)) {

        $pMmsg4 = $dbF->hardWords(
            'Please complete the security verification.',
            false
        );
    } else {
        $verifyUrl = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
        $verifyData = [
            'secret'   => $turnstileSecretKey,
            'response' => $turnstileToken
        ];
        if (!empty($_SERVER['REMOTE_ADDR'])) {
            $verifyData['remoteip'] = $_SERVER['REMOTE_ADDR'];
        }
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $verifyUrl,
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
        $curlError      = curl_error($curl);
        $httpCode       = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $turnstileResult = json_decode($verifyResponse, true);
        $turnstileVerified =
            empty($curlError) &&
            $httpCode === 200 &&
            is_array($turnstileResult) &&
            !empty($turnstileResult['success']) &&
            isset($turnstileResult['action']) &&
            hash_equals(
                'contact_form',
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
             * Get form fields.
             */
            $nameUser = isset($_POST['form']['name'])
                ? trim($_POST['form']['name'])
                : '';

            $emailUser = isset($_POST['form']['email'])
                ? trim($_POST['form']['email'])
                : '';

            $messageUser = isset($_POST['form']['message'])
                ? trim($_POST['form']['message'])
                : '';

            /*
             * Server-side validation.
             */
            if (
                $nameUser === '' ||
                $emailUser === '' ||
                $messageUser === ''
            ) {

                $pMmsg4 = $dbF->hardWords(
                    'Please fill in all required fields.',
                    false
                );

            } elseif (!filter_var($emailUser, FILTER_VALIDATE_EMAIL)) {

                $pMmsg4 = $dbF->hardWords(
                    'Please enter a valid email address.',
                    false
                );

            } else {
                $formData = [
                    'name'    => $nameUser,
                    'email'   => $emailUser,
                    'message' => $messageUser
                ];

                $databaseFields = [];
                $databaseValues = [];
                $fieldCounter   = 1;

                $mailMessage = '
                    <table
                        border="1"
                        cellpadding="8"
                        cellspacing="0"
                        style="border-collapse:collapse;width:100%;"
                    >
                ';

                foreach ($formData as $key => $value) {

                    $fieldTitle = ucwords(
                        str_replace('_', ' ', $key)
                    );

                    $databaseFields[] = 'field' . $fieldCounter . ' = ?';

                    $databaseValues[] = $fieldTitle . ':' . $value;

                    $mailMessage .= '
                        <tr>
                            <td style="font-weight:bold;width:30%;">
                                ' . htmlspecialchars(
                                    $fieldTitle,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) . '
                            </td>

                            <td>
                                ' . nl2br(
                                    htmlspecialchars(
                                        $value,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    )
                                ) . '
                            </td>
                        </tr>
                    ';

                    $fieldCounter++;
                }
                $mailMessage .= '
                    <tr>
                        <td style="font-weight:bold;">
                            Date Time
                        </td>

                        <td>
                            ' . date('D j M Y g:i a') . '
                        </td>
                    </tr>
                ';
                $mailMessage .= '</table>';
                $databaseFields[] = 'type = ?';
                $databaseValues[] = 'Contact form';
                $sql = '
                    INSERT INTO `surveyFormData`
                    SET ' . implode(', ', $databaseFields);

                $dbF->setRow(
                    $sql,
                    $databaseValues,
                    false
                );
                $adminEmail = $functions->ibms_setting('Email');
                // $adminEmail = 'sburhanali13@gmail.com';
                $functions->send_mail(
                    $adminEmail,
                    'Contact Form',
                    $mailMessage
                );
                $thankT = $dbF->hardWords(
                    'Thanks for your interest. Our representative will get in touch with you.',
                    false
                );
                $userMailSent = $functions->send_mail(
                    $emailUser,
                    '',
                    '',
                    'contactFormSubmit',
                    $nameUser
                );
                if ($userMailSent) {
                    $pMmsg4 = $thankT;
                    $_POST['form'] = [];
                } else {

                    $pMmsg4 = $dbF->hardWords(
                        'Your form was submitted, but the confirmation email could not be sent.',
                        false
                    );
                }
            }
        }
    }
}
?>
<div class="industify_fn_contact">
    <div class="container">
        <div class="contact_in">

            <div class="map_holder">
                <iframe width="100%" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3356.2850172939516!2d67.02342851059457!3d24.85488297784087!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3eb33e738e30b223%3A0xce7bdd25e07a6c6c!2sLakson%20Square%20Building%20No.%202!5e1!3m2!1sen!2s!4v1781782358242!5m2!1sen!2s"></iframe>
            </div>

            <div class="contact_holder">
                <div class="contact_left">
                    <h3>Get in touch with us</h3>
                    <?php
                    if (!empty($pMmsg4)) {
                        echo "<div style='text-align: center;
                        padding: 20px; margin-bottom: 20px; border: 1px solid transparent;
                        border-radius: 4px; background-color: #d9edf7; border-color: #bce8f1;'>
                        <p class='submit_msg' >" . $pMmsg4 . "</p></div>";
                    }
                    ?>
                    <form class="contact_formsss" method="post">
                        <div class="success" data-success="Your message has been received, we will contact you soon.">
                        </div>
                        <div class="empty_notice"><span>Please Fill Required Fields</span></div>
                        <div class="items">
                            <div class="item">
                                <input id="name" name='form[name]' type="text" placeholder="Name" required/>
                            </div>
                            <div class="item">
                                <input id="email" name='form[email]' type="email" placeholder="Email" required/>
                            </div>
                            <div class="item">
                                <textarea id="message" name='form[message]' placeholder="Message" required></textarea>
                            </div>
                            <div
                                class="cf-turnstile"
                                data-sitekey="0x4AAAAAAC-YdURxteUuxXmh"
                                data-action="contact_form"
                                data-theme="auto"
                            ></div>
                            <div class="item">
                                <button type="submit" id="send_message">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="contact_right">

                    <div class="fn_cs_location_list">
                        <ul class="list">

                            <li class="location_item">
                                <div class="item">
                                    <div class="title_holder">
                                        <span class="icon_wrapper">
                                            <span class="icon">
                                                <img class="fn__svg" src="svg/location.svg" alt="svg" />
                                            </span>
                                            <span class="shape"></span>
                                        </span>
                                        <h3>Head Office (Karachi)</h3>
                                    </div>
                                    <div class="content_holder">
                                        <ul>
                                            <li>Lakson Square Building No: 2, Sarwar Shaheed Road, Karachi – 74200, Pakistan</li>
                                            <li>Phone: <a href="tel:+922138400000">+92 21 3840 0000</a></li>
                                            <li>Fax: +92 21 3568 1163</li>
                                            <li>Email: <a href="mailto:info@centurypaper.com.pk">info@centurypaper.com.pk</a></li>
                                            <li style="margin-top: 10px;"><a href="https://www.google.com/maps/search/?api=1&query=Lakson+Square+Building+No.+2+Karachi" target="_blank" style="color: #45a2df; font-weight: 500;"><i class="fa-solid fa-map-location-dot"></i> View on Google Maps</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
 
                            <li class="location_item">
                                <div class="item">
                                    <div class="title_holder">
                                        <span class="icon_wrapper">
                                            <span class="icon">
                                                <img class="fn__svg" src="svg/location.svg" alt="svg" />
                                            </span>
                                            <span class="shape"></span>
                                        </span>
                                        <h3>Regional Office (Lahore)</h3>
                                    </div>
                                    <div class="content_holder">
                                        <ul>
                                            <li>14-Ali Block, New Garden Town, Lahore, Pakistan.</li>
                                            <li>Phone: <a href="tel:+924235886801">(+92 42) 3588 6801 -4</a></li>
                                            <li>Email: <a href="mailto:info@centurypaper.com.pk">info@centurypaper.com.pk</a></li>
                                            <li style="margin-top: 10px;"><a href="https://www.google.com/maps/search/?api=1&query=14-Ali+Block+New+Garden+Town+Lahore" target="_blank" style="color: #45a2df; font-weight: 500;"><i class="fa-solid fa-map-location-dot"></i> View on Google Maps</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
 
                            <li class="location_item">
                                <div class="item">
                                    <div class="title_holder">
                                        <span class="icon_wrapper">
                                            <span class="icon">
                                                <img class="fn__svg" src="svg/location.svg" alt="svg" />
                                            </span>
                                            <span class="shape"></span>
                                        </span>
                                        <h3>Integrated Mill (Kasur)</h3>
                                    </div>
                                    <div class="content_holder">
                                        <ul>
                                            <li>62-KM, Lahore-Upper Chenab Canal Bank Road, District Kasur, Pakistan.</li>
                                            <li>Phone: <a href="tel:+92494510061">(+92 49) 451 0061 -2, 4-5</a></li>
                                            <li>Email: <a href="mailto:info@centurypaper.com.pk">info@centurypaper.com.pk</a></li>
                                            <li style="margin-top: 10px;"><a href="https://www.google.com/maps/search/?api=1&query=Century+Paper+%26+Board+Mills+Ltd+Kasur" target="_blank" style="color: #45a2df; font-weight: 500;"><i class="fa-solid fa-map-location-dot"></i> View on Google Maps</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Contact -->
<?php
return ob_get_clean();
?>