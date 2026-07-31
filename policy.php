<?php include_once("header.php"); ?>

<!-- Page Title -->
<div class="industify_fn_pagetitle innovative_banner">
    <div class="banner_bg_shapes">
        <div class="bg_shape grid_pattern"></div>
        <div class="bg_shape glow_1"></div>
        <div class="bg_shape glow_2"></div>
    </div>
    <div class="banner_decorations">
        <!-- Floating Leaf 1 -->
        <div class="decor_item leaf_1">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M2 22C2 22 6 18 12 17C18 16 22 12 22 2C22 2 12 2 7 8C2 14 2 22 2 22Z" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M2 22C10 20 16 16 22 2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <!-- Floating Leaf 2 -->
        <div class="decor_item leaf_2">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M2 22C2 22 6 18 12 17C18 16 22 12 22 2C22 2 12 2 7 8C2 14 2 22 2 22Z" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M2 22C10 20 16 16 22 2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <!-- Recycling Symbol -->
        <div class="decor_item recycling_icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M4 12V20C4 20.5523 4.44772 21 5 21H13" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M20 12V4C20 3.44772 19.5523 3 19 3H11" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16 8L20 4L16 0" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M8 16L4 20L8 24" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M17 13.5L12 22L7 13.5H17Z" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <!-- Floating Paper Sheet 1 -->
        <div class="decor_item paper_1">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path
                    d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z"
                    stroke-linecap="round" stroke-linejoin="round" />
                <path d="M14 2V8H20" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16 13H8" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16 17H8" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M10 9H8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>
    <div class="container">
        <div class="title_holder">
            <h3>Company Policies</h3>
            <div class="industify_fn_breadcrumbs">
                <ul>
                    <li><a href="index.php" title="Home">Home</a></li>
                    <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
                    <li><a href="corporate-governance.php">Corporate Governance</a></li>
                    <li class="separator"><i class="fa-solid fa-angle-right"></i></li>
                    <li><span class="bread-current">Policies</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- /Page Title -->

<!-- Custom Styles for Policies Grid -->
<style>
    .policies_grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
        margin-top: 40px;
    }
    .policy_card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
    .policy_card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px -8px rgba(0, 51, 102, 0.15);
        border-color: #003366;
    }
    .policy_card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: #003366;
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .policy_card:hover::before {
        transform: scaleY(1);
    }
    .policy_header {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 20px;
    }
    .policy_icon {
        background: #fff5f5;
        color: #e53e3e;
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
        transition: all 0.3s ease;
        border: 1px solid #fed7d7;
    }
    .policy_card:hover .policy_icon {
        background: #e53e3e;
        color: #ffffff;
        border-color: #e53e3e;
    }
    .policy_info h4 {
        font-family: 'Outfit', sans-serif;
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 6px 0;
        line-height: 1.4;
        transition: color 0.3s ease;
    }
    .policy_card:hover .policy_info h4 {
        color: #003366;
    }
    .policy_number {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8;
        font-weight: 600;
    }
    .policy_footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        border-top: 1px solid #f1f5f9;
        padding-top: 15px;
        margin-top: 10px;
    }
    .view_pdf_btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: 'Outfit', sans-serif;
        font-size: 13px;
        font-weight: 600;
        color: #003366;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .view_pdf_btn i {
        font-size: 14px;
        transition: transform 0.3s ease;
    }
    .policy_card:hover .view_pdf_btn {
        color: #e53e3e;
    }
    .policy_card:hover .view_pdf_btn i {
        transform: translateX(3px);
    }
</style>

<!-- Policies Section -->
<div class="governance_section pb-80">
    <div class="container">
        
        <div class="governance_intro">
            <div class="ca_modern_heading">
                <div class="ghost">POLICIES</div>
                <span class="tag">Rules, Standards &amp; Frameworks</span>
                <h3 class="title">Company <span>Policies</span></h3>
                <div class="line"></div>
            </div>
            <div class="blueprint_desc">
                <p>Century Paper operates with total transparent governance, adhering to a comprehensive set of corporate policies to align our operations with clean, ethical, and responsible standards.</p>
            </div>
        </div>

        <!-- Policies Grid -->
        <div class="policies_grid">

            <!-- Card 1 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 01</span>
                        <h4>IMS Policy (Issue # 02)</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/IMS Policy Issue %23 02.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 02</span>
                        <h4>LMS Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/2. LMS policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 03</span>
                        <h4>Code of Business Principles</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/2-Code of Business Principles.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 04</span>
                        <h4>Century Ethical Trade Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/3-Century Ethical Trade Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 05</span>
                        <h4>FSC CoC Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/4-FSC CoC Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 06</span>
                        <h4>Social &amp; Environmental Responsibility Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/5-Century Social And Environmental Responsibility Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 7 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 07</span>
                        <h4>Record Safety &amp; Security Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/6- Record Safety and Security Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 8 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 08</span>
                        <h4>Conflict of Interest Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/7- Century's Conflict of Interest Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 9 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 09</span>
                        <h4>Social Media Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/8- Century Social Media Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 10 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 10</span>
                        <h4>Sustainability Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/9-Sustainability Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 11 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 11</span>
                        <h4>Gender Diversity Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/10- Gender Diversity Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 12 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 12</span>
                        <h4>Workplace Harassment Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/11-Work Place Harassment Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 13 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 13</span>
                        <h4>Human Resource Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/12- Century's Human Resource Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 14 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 14</span>
                        <h4>Child Labour Prevention Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/13- Child Labour Prevention Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 15 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 15</span>
                        <h4>Forced &amp; Bonded Labor Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/Forced and Bonded Labour Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 16 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 16</span>
                        <h4>Group Insurance Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/Group Insurance Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 17 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 17</span>
                        <h4>Overtime Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/Over Time Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 18 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 18</span>
                        <h4>SOP for Attendance Contractual Workers</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/SOP for Attendance Contractual Workers Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 19 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 19</span>
                        <h4>SOP for Misconduct Handling</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/Standard Operating Procedure (SOP) for Misconduct Handling.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 20 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 20</span>
                        <h4>Freedom of Association Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/19-Freedom of Association Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 21 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 21</span>
                        <h4>Information Security Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/Information Security Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 22 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 22</span>
                        <h4>Clear Desk - Clear Screen Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/22-Clear Desk -Clear Screen Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 23 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 23</span>
                        <h4>Access Control Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/23-Access Control Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 24 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 24</span>
                        <h4>Whistle Blowing Policy &amp; Procedure</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/24-Whistle Blowing Policy & Procedure.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 25 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 25</span>
                        <h4>Inside Trading Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/25-Inside Trading Policy.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Card 26 -->
            <div class="policy_card">
                <div class="policy_header">
                    <div class="policy_icon">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="policy_info">
                        <span class="policy_number">Policy 26</span>
                        <h4>Human Rights Policy</h4>
                    </div>
                </div>
                <div class="policy_footer">
                    <a href="webImages/policies/26-Human Rights Policy_20251001_0001.pdf" target="_blank" class="view_pdf_btn">
                        View Document <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>

<?php include_once("footer.php"); ?>
