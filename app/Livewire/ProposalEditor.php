<?php

namespace App\Livewire;

use App\Models\Proposal;
use Livewire\Component;

class ProposalEditor extends Component
{
    public Proposal $proposal;

    public string $title;

    public string $theme;

    public int $activeSlideIndex = 0;

    public bool $addSlideMenuOpen = false;

    public string $viewMode = 'responsive';

    public string $heading = '';

    public string $subheading = '';

    public string $body = '';

    public array $bodyHighlights = [];

    public string $bodyFooter = '';

    public string $quote = '';

    public string $author = '';

    public string $col1 = '';

    public string $col2 = '';

    public array $cardTitles = [];

    public array $cardBodies = [];

    public string $tagline = '';

    public string $line1 = '';

    public string $line2 = '';

    public string $line3 = '';

    public string $top_heading = '';

    public string $website = '';

    public array $bullets = [];

    public string $pill = '';

    public array $problems = [];

    public array $solutionTitles = [];

    public array $solutionDescs = [];

    public string $packageName = '';

    public string $idealFor = '';

    public string $revision = '';

    public string $benefit = '';

    public array $tags = [];

    public array $whatYouGet = [];

    public array $inclusions = [];

    public string $paymentRow1Pct = '';

    public string $paymentRow1Desc = '';

    public string $paymentRow2Pct = '';

    public string $paymentRow2Desc = '';

    public array $termsBullets = [];

    public array $clientBullets = [];

    public array $liabilityBullets = [];

    public string $slaText = '';

    public string $project1Url = '';

    public string $project1Label = '';

    public string $project2Url = '';

    public string $project2Label = '';

    public string $project3Url = '';

    public string $project3Label = '';

    public string $portfolioUrl = '';

    public string $portfolioLabel = '';

    public array $organizations = [];

    public string $testimonial1 = '';

    public string $testimonial2 = '';

    public string $testimonial3 = '';

    public string $ctaText = '';

    public string $ctaUrl = '';

    public string $email = '';

    public string $phone = '';

    protected array $rules = [
        'title' => 'required|string|max:255',
        'theme' => 'required|in:midnight,aurora,slate,rose,forest',
        'heading' => 'nullable|string',
        'subheading' => 'nullable|string',
        'body' => 'nullable|string',
        'quote' => 'nullable|string',
        'author' => 'nullable|string',
        'col1' => 'nullable|string',
        'col2' => 'nullable|string',
    ];

    public function mount(Proposal $proposal): void
    {
        abort_unless($proposal->user_id === auth()->id(), 403);

        $this->proposal = $proposal->load('slides');
        $this->title = $proposal->title;
        $this->theme = $proposal->theme;
        $this->loadSlide(0);
    }

    public function loadSlide(int $index): void
    {
        $this->activeSlideIndex = $index;
        $slide = $this->proposal->slides->get($index);
        if (! $slide) {
            return;
        }

        $c = $slide->content ?? [];
        $layout = $slide->layout ?? '';

        // Default values based on layout
        $defaults = $this->getDefaultsForLayout($layout);

        $this->heading = $c['heading'] ?? $defaults['heading'] ?? '';
        if ($layout === 'fixed-whois') {
            // Legacy / mistaken saves used literal backslash-n (PHP single-quoted defaults); normalize for the textarea.
            $this->heading = str_replace('\n', "\n", $this->heading);
        }
        $this->subheading = $c['subheading'] ?? $defaults['subheading'] ?? '';
        $this->body = $c['body'] ?? $defaults['body'] ?? '';
        $this->bodyHighlights = is_array($c['bodyHighlights'] ?? null) ? $c['bodyHighlights'] : ($defaults['bodyHighlights'] ?? []);
        $this->bodyFooter = $c['bodyFooter'] ?? $defaults['bodyFooter'] ?? '';
        $this->quote = $c['quote'] ?? $defaults['quote'] ?? '';
        $this->author = $c['author'] ?? $defaults['author'] ?? '';
        $this->col1 = $c['col1'] ?? $defaults['col1'] ?? '';
        $this->col2 = $c['col2'] ?? $defaults['col2'] ?? '';

        // Strategy cards
        $this->cardTitles = [
            1 => $c['card1_title'] ?? $defaults['card1_title'] ?? '',
            2 => $c['card2_title'] ?? $defaults['card2_title'] ?? '',
            3 => $c['card3_title'] ?? $defaults['card3_title'] ?? '',
            4 => $c['card4_title'] ?? $defaults['card4_title'] ?? '',
            5 => $c['card5_title'] ?? $defaults['card5_title'] ?? '',
        ];

        $this->cardBodies = [
            1 => $c['card1_body'] ?? $defaults['card1_body'] ?? '',
            2 => $c['card2_body'] ?? $defaults['card2_body'] ?? '',
            3 => $c['card3_body'] ?? $defaults['card3_body'] ?? '',
            4 => $c['card4_body'] ?? $defaults['card4_body'] ?? '',
            5 => $c['card5_body'] ?? $defaults['card5_body'] ?? '',
        ];

        // Fixed template fields
        $this->tagline = $c['tagline'] ?? $defaults['tagline'] ?? '';
        $this->line1 = $c['line1'] ?? $defaults['line1'] ?? '';
        $this->line2 = $c['line2'] ?? $defaults['line2'] ?? '';
        $this->line3 = $c['line3'] ?? $defaults['line3'] ?? '';

        $this->top_heading = $c['top_heading'] ?? $defaults['top_heading'] ?? '';
        $this->website = $c['website'] ?? $defaults['website'] ?? '';

        $bullets = $c['bullets'] ?? $defaults['bullets'] ?? [];
        $this->bullets = array_values(is_array($bullets) ? $bullets : []);
        for ($i = 0; $i < 6; $i++) {
            $this->bullets[$i] = $this->bullets[$i] ?? '';
        }

        $this->pill = $c['pill'] ?? $defaults['pill'] ?? '';
        $this->problems = [
            0 => $c['problem1'] ?? $defaults['problem1'] ?? '',
            1 => $c['problem2'] ?? $defaults['problem2'] ?? '',
            2 => $c['problem3'] ?? $defaults['problem3'] ?? '',
            3 => $c['problem4'] ?? $defaults['problem4'] ?? '',
            4 => $c['problem5'] ?? $defaults['problem5'] ?? '',
        ];

        $this->solutionTitles = [];
        $this->solutionDescs = [];
        for ($i = 1; $i <= 5; $i++) {
            $this->solutionTitles[$i] = $c["solution{$i}_title"] ?? $defaults["solution{$i}_title"] ?? '';
            $this->solutionDescs[$i] = $c["solution{$i}_desc"] ?? $defaults["solution{$i}_desc"] ?? '';
        }

        $this->packageName = $c['packageName'] ?? $defaults['packageName'] ?? '';
        $this->idealFor = $c['idealFor'] ?? $defaults['idealFor'] ?? '';
        $this->revision = $c['revision'] ?? $defaults['revision'] ?? '';
        $this->benefit = $c['benefit'] ?? $defaults['benefit'] ?? '';
        $this->tags = $c['tags'] ?? $defaults['tags'] ?? [];
        $this->whatYouGet = $c['whatYouGet'] ?? $defaults['whatYouGet'] ?? [];
        $this->inclusions = $c['inclusions'] ?? $defaults['inclusions'] ?? [];

        $this->paymentRow1Pct = $c['payment_row1_pct'] ?? $defaults['payment_row1_pct'] ?? '';
        $this->paymentRow1Desc = $c['payment_row1_desc'] ?? $defaults['payment_row1_desc'] ?? '';
        $this->paymentRow2Desc = $c['payment_row2_desc'] ?? '';
        $this->termsBullets = is_array($c['terms_bullets'] ?? null) ? $c['terms_bullets'] : [];
        $this->clientBullets = is_array($c['client_bullets'] ?? null) ? $c['client_bullets'] : [];
        $this->liabilityBullets = is_array($c['liability_bullets'] ?? null) ? $c['liability_bullets'] : [];
        $this->slaText = $c['sla_text'] ?? '';

        $this->project1Url = $c['project1_url'] ?? '';
        $this->project1Label = $c['project1_label'] ?? '';
        $this->project2Url = $c['project2_url'] ?? '';
        $this->project2Label = $c['project2_label'] ?? '';
        $this->project3Url = $c['project3_url'] ?? '';
        $this->project3Label = $c['project3_label'] ?? '';
        $this->portfolioUrl = $c['portfolio_url'] ?? '';
        $this->portfolioLabel = $c['portfolio_label'] ?? '';

        $this->organizations = is_array($c['organizations'] ?? null) ? $c['organizations'] : [];
        for ($i = 0; $i < 12; $i++) {
            $this->organizations[$i] = $this->organizations[$i] ?? '';
        }

        $this->testimonial1 = $c['testimonial1'] ?? '';
        $this->testimonial2 = $c['testimonial2'] ?? '';
        $this->testimonial3 = $c['testimonial3'] ?? '';

        $this->ctaText = $c['cta_text'] ?? '';
        $this->ctaUrl = $c['cta_url'] ?? '';
        $this->email = $c['email'] ?? '';
        $this->phone = $c['phone'] ?? '';
    }

    /**
     * Full content overlay for the active slide (matches the main canvas preview).
     */
    public function activeSlidePreviewContent(): array
    {
        $slide = $this->proposal->slides->get($this->activeSlideIndex);
        if (! $slide) {
            return [];
        }

        $bullets = array_values($this->bullets);
        for ($i = 0; $i < 6; $i++) {
            $bullets[$i] = $bullets[$i] ?? '';
        }

        $organizations = $this->organizations;
        for ($i = 0; $i < 12; $i++) {
            $organizations[$i] = $organizations[$i] ?? '';
        }

        return array_merge($slide->content ?? [], [
            'heading' => $this->heading,
            'subheading' => $this->subheading,
            'body' => $this->body,
            'bodyHighlights' => array_values($this->bodyHighlights),
            'bodyFooter' => $this->bodyFooter,
            'quote' => $this->quote,
            'author' => $this->author,
            'col1' => $this->col1,
            'col2' => $this->col2,
            'card1_title' => $this->cardTitles[1] ?? '',
            'card2_title' => $this->cardTitles[2] ?? '',
            'card3_title' => $this->cardTitles[3] ?? '',
            'card4_title' => $this->cardTitles[4] ?? '',
            'card5_title' => $this->cardTitles[5] ?? '',
            'card1_body' => $this->cardBodies[1] ?? '',
            'card2_body' => $this->cardBodies[2] ?? '',
            'card3_body' => $this->cardBodies[3] ?? '',
            'card4_body' => $this->cardBodies[4] ?? '',
            'card5_body' => $this->cardBodies[5] ?? '',
            'tagline' => $this->tagline,
            'line1' => $this->line1,
            'line2' => $this->line2,
            'line3' => $this->line3,
            'top_heading' => $this->top_heading,
            'website' => $this->website,
            'bullets' => $bullets,
            'pill' => $this->pill,
            'problem1' => $this->problems[0] ?? '',
            'problem2' => $this->problems[1] ?? '',
            'problem3' => $this->problems[2] ?? '',
            'problem4' => $this->problems[3] ?? '',
            'problem5' => $this->problems[4] ?? '',
            'solution1_title' => $this->solutionTitles[1] ?? '',
            'solution1_desc' => $this->solutionDescs[1] ?? '',
            'solution2_title' => $this->solutionTitles[2] ?? '',
            'solution2_desc' => $this->solutionDescs[2] ?? '',
            'solution3_title' => $this->solutionTitles[3] ?? '',
            'solution3_desc' => $this->solutionDescs[3] ?? '',
            'solution4_title' => $this->solutionTitles[4] ?? '',
            'solution4_desc' => $this->solutionDescs[4] ?? '',
            'solution5_title' => $this->solutionTitles[5] ?? '',
            'solution5_desc' => $this->solutionDescs[5] ?? '',
            'packageName' => $this->packageName,
            'idealFor' => $this->idealFor,
            'revision' => $this->revision,
            'benefit' => $this->benefit,
            'tags' => $this->tags,
            'whatYouGet' => $this->whatYouGet,
            'inclusions' => $this->inclusions,
            'payment_row1_pct' => $this->paymentRow1Pct,
            'payment_row1_desc' => $this->paymentRow1Desc,
            'payment_row2_pct' => $this->paymentRow2Pct,
            'payment_row2_desc' => $this->paymentRow2Desc,
            'terms_bullets' => array_values($this->termsBullets),
            'client_bullets' => array_values($this->clientBullets),
            'liability_bullets' => array_values($this->liabilityBullets),
            'sla_text' => $this->slaText,
            'project1_url' => $this->project1Url,
            'project1_label' => $this->project1Label,
            'project2_url' => $this->project2Url,
            'project2_label' => $this->project2Label,
            'project3_url' => $this->project3Url,
            'project3_label' => $this->project3Label,
            'portfolio_url' => $this->portfolioUrl,
            'portfolio_label' => $this->portfolioLabel,
            'organizations' => array_values($organizations),
            'testimonial1' => $this->testimonial1,
            'testimonial2' => $this->testimonial2,
            'testimonial3' => $this->testimonial3,
            'cta_text' => $this->ctaText,
            'cta_url' => $this->ctaUrl,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);
    }

    private function getDefaultsForLayout(string $layout): array
    {
        return match ($layout) {
            'fixed-cover' => [
                'tagline' => 'Your Partner Towards Digital Innovation',
                'line1' => 'WEBSITE',
                'line2' => 'DEVELOPMENT',
                'line3' => 'PROPOSAL',
            ],
            'fixed-executive' => [
                'heading' => 'Executive Summary',
                'body' => "Odecci Solutions Inc., is committed to delivering innovative, user-centric digital solutions that empower businesses to thrive in today's competitive landscape. This proposal outlines our comprehensive website development service designed to enhance your brand presence, improve customer engagement, and drive measurable business growth.\n\nOur approach combines cutting-edge technology, intuitive design, and strategic functionality to create a website that is not only visually appealing but also optimized for performance, security, and scalability. By leveraging modern frameworks and best practices, we ensure your website becomes a powerful tool for marketing, communication, and conversion.",
                'bodyHighlights' => [
                    '**Custom Design & Branding:** Tailored to reflect your unique identity and values.',
                    '**Responsive & Mobile-First Development:** Seamless experience across all devices.',
                    '**SEO & Performance Optimization:** Enhanced visibility and faster load times.',
                    '**Content Management System (CMS):** Easy updates and scalability for future growth.',
                    '**Security & Compliance:** Robust measures to protect data and maintain trust.',
                    '**Analytics Integration:** Actionable insights to monitor and improve performance.',
                ],
                'bodyFooter' => 'Partnering with Odecci means gaining a strategic ally focused on delivering a website that aligns with your business objectives, strengthens your digital footprint, and creates lasting impact. Our team ensures timely delivery, transparent communication, and ongoing support to maximize your investment.',
            ],
            'fixed-whois' => [
                'top_heading' => 'OUR STRATEGY',
                'heading' => "Who is\nOdecci?",
                'website' => 'www.odecci.com',
                'bullets' => [
                    'Client-Centric Solutions',
                    'Data-Driven Decision Making',
                    'Agile Development',
                    'Sustainable Growth',
                    'Collaborative Partnership',
                    'Support & Maintenance',
                ],
            ],
            'fixed-strategy-cards' => [
                'heading' => 'Our Strategy',
                'subheading' => "We understand that every business has\nunique goals for its system, such as:",
                'card1_title' => 'Hand Tailored Solutions',
                'card1_body' => 'Design websites that are uniquely customized to align with each client\'s specific business needs, from branded interfaces to intricate technical functionalities, ensuring a perfect fit for their operations.',
                'card2_title' => 'Enhance Client Collaboration',
                'card2_body' => 'Integrate closely with clients throughout the support process, fostering a partnership that incorporates their vision and feedback to create solutions that reflect their goals.',
                'card3_title' => 'Boost Business Performance',
                'card3_body' => 'Develop a maintenance and support process that drives measurable outcomes, such as increased website performance and improved visibility.',
                'card4_title' => 'Ensure Exceptional User Experience',
                'card4_body' => 'Create intuitive, visually appealing interfaces that enhance user engagement and satisfaction, making the application both functional and accessible for end-users.',
                'card5_title' => 'Provide Strategic Implementation',
                'card5_body' => 'Support clients with comprehensive strategies, including case studies and development roadmaps, to ensure seamless deployment and long-term success of the website.',
            ],
            'fixed-problem-statement' => [
                'heading' => 'Problem Statement',
                'body' => "In today's digital-first environment, businesses face numerous obstacles that hinder their ability to maintain a strong online presence. From outdated designs and poor user experience to security risks and low search visibility, these issues can significantly impact brand credibility and growth. Odecci's website development services are designed to address these challenges head-on, providing innovative, scalable, and secure solutions that align with your business objectives.",
                'pill' => 'Top 5 most common problems encountered',
                'problem1' => 'Lack of Professional Online Presence',
                'problem2' => 'Poor User Experience and Navigation',
                'problem3' => 'Limited Scalability and Content Management',
                'problem4' => 'Low Search Engine Visibility',
                'problem5' => 'Security Vulnerabilities and Compliance Risks',
            ],
            'fixed-custom-solution' => [
                'heading' => 'Our Custom Solution',
                'body' => 'Odecci delivers a next-generation website development service that pairs custom design with advanced technology and strategic functionality — giving you a fast, secure, and scalable site that supports your brand and your customers at every touchpoint.',
                'solution1_title' => 'Seamless User Experience',
                'solution1_desc' => 'Intuitive navigation and mobile-first design for maximum engagement.',
                'solution2_title' => 'Scalable Architecture',
                'solution2_desc' => 'Built on a robust CMS for easy updates and future growth.',
                'solution3_title' => 'Enhanced Visibility',
                'solution3_desc' => 'Integrated SEO strategies to boost search rankings and attract organic traffic.',
                'solution4_title' => 'Enterprise-Level Security',
                'solution4_desc' => 'Strong protection against cyber threats and compliance with data standards.',
                'solution5_title' => 'Actionable Insights',
                'solution5_desc' => 'Analytics integration for informed decision-making and continuous improvement.',
            ],
            'fixed-scope-basic' => [
                'packageName' => 'Basic Website Package',
                'idealFor' => 'Small to Medium Businesses looking for a professional online presence without complexity.',
                'revision' => '1 Design Revision',
                'benefit' => "What You'll Get",
                'tags' => ['Tailored Design', '2 Weeks Delivery'],
                'whatYouGet' => [
                    ['title' => '1-3', 'desc' => 'Mobile Friendly Design'],
                    ['title' => 'Content Management System (CMS)', 'desc' => 'Effortless content updates and scalability.'],
                    ['title' => 'Basic SEO Setup', 'desc' => 'Improve search visibility and attract organic traffic.'],
                    ['title' => 'Google Analytics Integration', 'desc' => 'Track performance and user behavior for data-driven decisions.'],
                ],
                'inclusions' => [
                    ['title' => '1 Month Free Support', 'desc' => 'Post-launch assistance for smooth operations.'],
                    ['title' => 'Google Analytics Setup', 'desc' => 'Track visitor behavior and performance.'],
                    ['title' => 'Google Business Profile Setup', 'desc' => 'Enhance your local search presence.'],
                    ['title' => '3 Personalized Business Email Addresses', 'desc' => 'Professional communication for your team.'],
                    ['title' => 'Free Web Hosting for 1 Year', 'desc' => 'Reliable and secure hosting.'],
                    ['title' => 'Free Domain (.com) with SSL for 1 Year', 'desc' => 'Secure and professional online identity.'],
                    ['title' => 'Free CMS Training', 'desc' => '1-day, 2-hour live session to empower your team.'],
                ],
            ],
            'fixed-scope-business' => [
                'packageName' => 'Business Website Package',
                'idealFor' => 'Scaling businesses that want their brand to stand out, increase traffic, and showcase products and services with a professional, feature-rich website.',
                'revision' => '2 Design Revision',
                'benefit' => "You'll Get All from basic plus:",
                'tags' => ['Tailored Design', '4 Weeks Delivery', 'CRM Integration'],
                'whatYouGet' => [
                    ['title' => '3-6', 'desc' => 'Mobile-Friendly Pages'],
                    ['title' => 'Products/Services Page', 'desc' => 'Highlight your offerings with engaging layouts.'],
                    ['title' => 'Blog & News Page', 'desc' => 'Share updates, insights, and boost SEO.'],
                    ['title' => 'Content Management System (CMS)', 'desc' => 'Easy content updates and scalability.'],
                    ['title' => 'CRM Integration (HubSpot)', 'desc' => 'Streamline customer relationship management.'],
                    ['title' => 'Advanced SEO Setup', 'desc' => 'Drive organic traffic and improve search rankings.'],
                ],
                'inclusions' => [
                    ['title' => '2 Month Free Support', 'desc' => 'Extended assistance for smooth operations.'],
                    ['title' => 'Google Analytics Setup', 'desc' => 'Monitor performance and user behavior.'],
                    ['title' => 'Google Business Profile Setup', 'desc' => 'Strengthen your local presence.'],
                    ['title' => '5 Personalized Business Email Addresses', 'desc' => 'Professional communication for your team.'],
                    ['title' => 'Free Web Hosting for 1 Year', 'desc' => 'Reliable and secure hosting.'],
                    ['title' => 'Free Domain (.com) with SSL for 1 Year', 'desc' => 'Secure and professional online identity.'],
                    ['title' => 'Free CMS Training', 'desc' => '2-days with 2-hour live session to empower your team.'],
                ],
            ],
            'fixed-scope-store' => [
                'packageName' => 'Online Store Website Package',
                'idealFor' => 'Retailers, wholesalers, and brands that want to sell online with ease. This end-to-end eCommerce solution handles everything, from product display and secure payment processing to shipping and customer management, all in one powerful platform.',
                'revision' => '3 Design Revision',
                'benefit' => "You'll Get All from basic and business plus:",
                'tags' => ['Tailored Design', '8 Weeks Delivery', 'CRM Integration', 'Payment Integration'],
                'whatYouGet' => [
                    ['title' => 'Unlimited Mobile-Friendly Design', 'desc' => 'Fully responsive layouts for an exceptional user experience across all devices.'],
                    ['title' => 'Full Inventory & Order Management', 'desc' => 'Manage stock, orders, and fulfillment seamlessly.'],
                    ['title' => 'Discounts, Coupons & Promotions', 'desc' => 'Engage customers with attractive offers.'],
                    ['title' => 'Cart + Checkout Page', 'desc' => 'Smooth and secure shopping experience.'],
                    ['title' => 'Maintainable Shipping Rates', 'desc' => 'Flexible shipping options for your customers.'],
                    ['title' => 'Online Payment Integration', 'desc' => 'Accept payments securely and conveniently.'],
                    ['title' => 'Customer Dashboard', 'desc' => 'Empower customers with account management tools.'],
                ],
                'inclusions' => [
                    ['title' => '3 Month Free Support', 'desc' => 'Extended assistance for smooth operations.'],
                    ['title' => 'Google Analytics Setup', 'desc' => 'Track visitor behavior and performance.'],
                    ['title' => 'Google Business Profile Setup', 'desc' => 'Enhance your local search presence.'],
                    ['title' => '10 Personalized Business Email Addresses', 'desc' => 'Professional communication for your team.'],
                    ['title' => 'Free Web Hosting for 1 Year', 'desc' => 'Reliable and secure hosting.'],
                    ['title' => 'Free Domain (.com) with SSL for 1 Year', 'desc' => 'Secure and professional online identity.'],
                    ['title' => 'Free CMS Training', 'desc' => '3-days, 2-hour live session to empower your team.'],
                ],
            ],
            'fixed-terms' => [
                'heading' => 'Terms And Condition',
                'payment_row1_pct' => '50% Downpayment',
                'payment_row1_desc' => 'Upon contract signing',
                'payment_row2_pct' => '50% Deployment',
                'payment_row2_desc' => 'Upon turnover and project acceptance.',
                'terms_bullets' => [
                    'Any out-of-scope work will be billed separately.',
                    'The billing invoice will be issued based on the accomplished milestone.',
                ],
                'client_bullets' => [
                    'Client shall provide a person to be the main contact for the project',
                    'The Client must supply content for updates (e.g., text, images, announcements).',
                ],
                'liability_bullets' => [
                    'The service provider should provide a progress report of the project every week.',
                    'Liability is limited to the total contract value of the project.',
                ],
                'sla_text' => 'Upon approval of this proposal, a detailed Service Level Agreement (SLA) will be executed to formalize the terms of service, ensuring clarity on the delivery, management, and support of the website throughout the contract period.',
            ],
            'fixed-projects' => [
                'heading' => 'Some of our Website Projects',
                'project1_url' => 'https://htms.com.ph/',
                'project1_label' => 'HTMS Website',
                'project2_url' => 'https://yskprojdev.ph/',
                'project2_label' => 'YSK Project',
                'project3_url' => 'https://srresidencesalmouj.com/',
                'project3_label' => 'St. Regis Residence',
                'portfolio_url' => 'https://odecci.com/odecci-portfolio/',
                'portfolio_label' => 'View Our Portfolio >>',
            ],
            'fixed-organizations' => [
                'heading' => 'Organizations we work with',
                'organizations' => array_map(fn ($i) => "Organization {$i}", range(1, 12)),
            ],
            'fixed-testimonial' => [
                'heading' => 'Testimonial',
                'testimonial1' => 'Testimonial 1',
                'testimonial2' => 'Testimonial 2',
                'testimonial3' => 'Testimonial 3',
            ],
            'fixed-guidance' => [
                'heading' => 'Need guidance?',
                'cta_text' => 'Or click this link and book now:',
                'cta_url' => 'https://odecci.com/consultation/',
            ],
            'fixed-contact' => [
                'heading' => 'Contact Us',
                'email' => 'info@odecci.com',
                'phone' => '+63 123 456 7890',
                'website' => 'www.odecci.com',
            ],
            'fixed-why-customize' => [
                'heading' => 'Why Your Business Needs a Customized Application',
                'body' => "Every business is unique, even if daily operations seem similar across industries. A custom application empowers businesses to stand out by addressing specific needs, such as attracting clients, streamlining internal management, enhancing marketing strategies, optimizing processes, or automating targeted sectors. Tailored solutions ensure your business operates efficiently and aligns with your distinct vision and goals.\n\nAt Odecci, we don't just build systems, we create platforms that work as smart, strategic tools for your business. Because in these fast-moving and competitive industries, you need to think ahead.",
            ],
            default => [],
        };
    }

    public function updated(string $fullPath, mixed $value): void
    {
        $root = str($fullPath)->before('.')->toString();
        if (in_array($root, [
            'heading',
            'subheading',
            'body',
            'bodyHighlights',
            'bodyFooter',
            'quote',
            'author',
            'col1',
            'col2',
            'cardTitles',
            'cardBodies',
            'tagline',
            'line1',
            'line2',
            'line3',
            'top_heading',
            'website',
            'bullets',
            'pill',
            'problems',
            'solutionTitles',
            'solutionDescs',
            'packageName',
            'idealFor',
            'revision',
            'benefit',
            'tags',
            'whatYouGet',
            'inclusions',
            'paymentRow1Pct',
            'paymentRow1Desc',
            'paymentRow2Pct',
            'paymentRow2Desc',
            'termsBullets',
            'clientBullets',
            'liabilityBullets',
            'slaText',
            'project1Url',
            'project1Label',
            'project2Url',
            'project2Label',
            'project3Url',
            'project3Label',
            'portfolioUrl',
            'portfolioLabel',
            'organizations',
            'testimonial1',
            'testimonial2',
            'testimonial3',
            'ctaText',
            'ctaUrl',
            'email',
            'phone',
        ], true)) {
            $this->saveCurrentSlide();
        }
    }

    public function saveCurrentSlide(): void
    {
        $slide = $this->proposal->slides->get($this->activeSlideIndex);
        if (! $slide) {
            return;
        }

        $layout = $slide->layout ?? '';
        $defaults = $this->getDefaultsForLayout($layout);

        $content = [];

        if (! empty($this->heading) && $this->heading !== ($defaults['heading'] ?? '')) {
            $content['heading'] = $this->heading;
        }
        if (! empty($this->subheading) && $this->subheading !== ($defaults['subheading'] ?? '')) {
            $content['subheading'] = $this->subheading;
        }

        if (! empty($this->body)) {
            $content['body'] = $this->body;
        }

        if (! empty($this->bodyHighlights)) {
            $content['bodyHighlights'] = array_values($this->bodyHighlights);
        }
        if (! empty($this->bodyFooter)) {
            $content['bodyFooter'] = $this->bodyFooter;
        }

        if (! empty($this->quote) && $this->quote !== ($defaults['quote'] ?? '')) {
            $content['quote'] = $this->quote;
        }
        if (! empty($this->author) && $this->author !== ($defaults['author'] ?? '')) {
            $content['author'] = $this->author;
        }
        if (! empty($this->col1) && $this->col1 !== ($defaults['col1'] ?? '')) {
            $content['col1'] = $this->col1;
        }
        if (! empty($this->col2) && $this->col2 !== ($defaults['col2'] ?? '')) {
            $content['col2'] = $this->col2;
        }

        for ($i = 1; $i <= 5; $i++) {
            if (! empty($this->cardTitles[$i]) && $this->cardTitles[$i] !== ($defaults["card{$i}_title"] ?? '')) {
                $content["card{$i}_title"] = $this->cardTitles[$i];
            }
            if (! empty($this->cardBodies[$i]) && $this->cardBodies[$i] !== ($defaults["card{$i}_body"] ?? '')) {
                $content["card{$i}_body"] = $this->cardBodies[$i];
            }
        }

        if (! empty($this->tagline) && $this->tagline !== ($defaults['tagline'] ?? '')) {
            $content['tagline'] = $this->tagline;
        }
        if (! empty($this->line1) && $this->line1 !== ($defaults['line1'] ?? '')) {
            $content['line1'] = $this->line1;
        }
        if (! empty($this->line2) && $this->line2 !== ($defaults['line2'] ?? '')) {
            $content['line2'] = $this->line2;
        }
        if (! empty($this->line3) && $this->line3 !== ($defaults['line3'] ?? '')) {
            $content['line3'] = $this->line3;
        }
        if (! empty($this->top_heading) && $this->top_heading !== ($defaults['top_heading'] ?? '')) {
            $content['top_heading'] = $this->top_heading;
        }
        if (! empty($this->website) && $this->website !== ($defaults['website'] ?? '')) {
            $content['website'] = $this->website;
        }

        $bullets = array_values(array_filter($this->bullets));
        if (! empty($bullets)) {
            $content['bullets'] = $bullets;
        }

        if (! empty($this->pill) && $this->pill !== ($defaults['pill'] ?? '')) {
            $content['pill'] = $this->pill;
        }

        for ($i = 0; $i < 5; $i++) {
            if (! empty($this->problems[$i]) && $this->problems[$i] !== ($defaults['problem'.($i + 1)] ?? '')) {
                $content['problem'.($i + 1)] = $this->problems[$i];
            }
        }

        for ($i = 1; $i <= 5; $i++) {
            if (! empty($this->solutionTitles[$i]) && $this->solutionTitles[$i] !== ($defaults["solution{$i}_title"] ?? '')) {
                $content["solution{$i}_title"] = $this->solutionTitles[$i];
            }
            if (! empty($this->solutionDescs[$i]) && $this->solutionDescs[$i] !== ($defaults["solution{$i}_desc"] ?? '')) {
                $content["solution{$i}_desc"] = $this->solutionDescs[$i];
            }
        }

        if ($this->packageName !== ($defaults['packageName'] ?? '')) {
            $content['packageName'] = $this->packageName;
        }
        if ($this->idealFor !== ($defaults['idealFor'] ?? '')) {
            $content['idealFor'] = $this->idealFor;
        }
        if ($this->revision !== ($defaults['revision'] ?? '')) {
            $content['revision'] = $this->revision;
        }
        if ($this->benefit !== ($defaults['benefit'] ?? '')) {
            $content['benefit'] = $this->benefit;
        }
        if (! empty($this->tags)) {
            $content['tags'] = $this->tags;
        }
        if (! empty($this->whatYouGet)) {
            $content['whatYouGet'] = $this->whatYouGet;
        }
        if (! empty($this->inclusions)) {
            $content['inclusions'] = $this->inclusions;
        }

        if (! empty($this->paymentRow1Pct) && $this->paymentRow1Pct !== ($defaults['payment_row1_pct'] ?? '')) {
            $content['payment_row1_pct'] = $this->paymentRow1Pct;
        }
        if (! empty($this->paymentRow1Desc) && $this->paymentRow1Desc !== ($defaults['payment_row1_desc'] ?? '')) {
            $content['payment_row1_desc'] = $this->paymentRow1Desc;
        }
        if (! empty($this->paymentRow2Pct) && $this->paymentRow2Pct !== ($defaults['payment_row2_pct'] ?? '')) {
            $content['payment_row2_pct'] = $this->paymentRow2Pct;
        }
        if (! empty($this->paymentRow2Desc) && $this->paymentRow2Desc !== ($defaults['payment_row2_desc'] ?? '')) {
            $content['payment_row2_desc'] = $this->paymentRow2Desc;
        }

        if (! empty(array_filter($this->termsBullets))) {
            $content['terms_bullets'] = array_values(array_filter($this->termsBullets));
        }
        if (! empty(array_filter($this->clientBullets))) {
            $content['client_bullets'] = array_values(array_filter($this->clientBullets));
        }
        if (! empty(array_filter($this->liabilityBullets))) {
            $content['liability_bullets'] = array_values(array_filter($this->liabilityBullets));
        }
        if (! empty($this->slaText) && $this->slaText !== ($defaults['sla_text'] ?? '')) {
            $content['sla_text'] = $this->slaText;
        }

        if (! empty($this->project1Url) && $this->project1Url !== ($defaults['project1_url'] ?? '')) {
            $content['project1_url'] = $this->project1Url;
        }
        if (! empty($this->project1Label) && $this->project1Label !== ($defaults['project1_label'] ?? '')) {
            $content['project1_label'] = $this->project1Label;
        }
        if (! empty($this->project2Url) && $this->project2Url !== ($defaults['project2_url'] ?? '')) {
            $content['project2_url'] = $this->project2Url;
        }
        if (! empty($this->project2Label) && $this->project2Label !== ($defaults['project2_label'] ?? '')) {
            $content['project2_label'] = $this->project2Label;
        }
        if (! empty($this->project3Url) && $this->project3Url !== ($defaults['project3_url'] ?? '')) {
            $content['project3_url'] = $this->project3Url;
        }
        if (! empty($this->project3Label) && $this->project3Label !== ($defaults['project3_label'] ?? '')) {
            $content['project3_label'] = $this->project3Label;
        }
        if (! empty($this->portfolioUrl) && $this->portfolioUrl !== ($defaults['portfolio_url'] ?? '')) {
            $content['portfolio_url'] = $this->portfolioUrl;
        }
        if (! empty($this->portfolioLabel) && $this->portfolioLabel !== ($defaults['portfolio_label'] ?? '')) {
            $content['portfolio_label'] = $this->portfolioLabel;
        }

        if (! empty(array_filter($this->organizations))) {
            $content['organizations'] = array_values(array_filter($this->organizations));
        }

        if (! empty($this->testimonial1) && $this->testimonial1 !== ($defaults['testimonial1'] ?? '')) {
            $content['testimonial1'] = $this->testimonial1;
        }
        if (! empty($this->testimonial2) && $this->testimonial2 !== ($defaults['testimonial2'] ?? '')) {
            $content['testimonial2'] = $this->testimonial2;
        }
        if (! empty($this->testimonial3) && $this->testimonial3 !== ($defaults['testimonial3'] ?? '')) {
            $content['testimonial3'] = $this->testimonial3;
        }

        if (! empty($this->ctaText) && $this->ctaText !== ($defaults['cta_text'] ?? '')) {
            $content['cta_text'] = $this->ctaText;
        }
        if (! empty($this->ctaUrl) && $this->ctaUrl !== ($defaults['cta_url'] ?? '')) {
            $content['cta_url'] = $this->ctaUrl;
        }
        if (! empty($this->email) && $this->email !== ($defaults['email'] ?? '')) {
            $content['email'] = $this->email;
        }
        if (! empty($this->phone) && $this->phone !== ($defaults['phone'] ?? '')) {
            $content['phone'] = $this->phone;
        }

        if (! empty($content)) {
            $slide->update(['content' => $content]);
        }

        $this->proposal->load('slides');
    }

    public function addBodyHighlight(): void
    {
        $this->bodyHighlights[] = '';
    }

    public function removeBodyHighlight(int $index): void
    {
        unset($this->bodyHighlights[$index]);
        $this->bodyHighlights = array_values($this->bodyHighlights);
        $this->saveCurrentSlide();
    }

    public function addWhatYouGetItem(): void
    {
        $this->whatYouGet[] = ['title' => '', 'desc' => ''];
    }

    public function removeWhatYouGetItem(int $index): void
    {
        unset($this->whatYouGet[$index]);
        $this->whatYouGet = array_values($this->whatYouGet);
        $this->saveCurrentSlide();
    }

    public function addInclusionItem(): void
    {
        $this->inclusions[] = ['title' => '', 'desc' => ''];
    }

    public function removeInclusionItem(int $index): void
    {
        unset($this->inclusions[$index]);
        $this->inclusions = array_values($this->inclusions);
        $this->saveCurrentSlide();
    }

    public function addTagItem(): void
    {
        $this->tags[] = '';
    }

    public function removeTagItem(int $index): void
    {
        unset($this->tags[$index]);
        $this->tags = array_values($this->tags);
        $this->saveCurrentSlide();
    }

    public function selectSlide(int $index): void
    {
        $this->saveCurrentSlide();
        $this->loadSlide($index);
    }

    public function addSlide(string $layout = 'content'): void
    {
        $this->addSlideMenuOpen = false;
        $this->saveCurrentSlide();

        $maxOrder = $this->proposal->slides()->max('order') ?? -1;
        $defaults = match ($layout) {
            'title' => ['heading' => 'New Title', 'subheading' => 'Subtitle here'],
            'content' => ['heading' => 'Section Title', 'body' => 'Your content goes here...'],
            'two-col' => ['heading' => 'Two Columns', 'col1' => 'Left column content...', 'col2' => 'Right column content...'],
            'quote' => ['quote' => '"A powerful quote goes here."', 'author' => '- Author Name'],
            'blank' => [],
            'fixed-cover' => [
                'tagline' => 'Your Partner Towards Digital Innovation',
                'line1' => 'WEBSITE',
                'line2' => 'DEVELOPMENT',
                'line3' => 'PROPOSAL',
            ],
            'fixed-executive' => [
                'heading' => 'Executive Summary',
            ],
            'fixed-whois' => [
                'top_heading' => 'OUR STRATEGY',
                'heading' => "Who is\nOdecci?",
                'body' => 'Your who-is text...',
                'website' => 'www.odecci.com',
                'bullets' => [
                    'Client-Centric Solutions',
                    'Data-Driven Decision Making',
                    'Agile Development',
                    'Sustainable Growth',
                    'Collaborative Partnership',
                    'Support & Maintenance',
                ],
            ],
            'fixed-strategy-cards' => [
                'heading' => 'Our Strategy',
                'subheading' => "We understand that every business has\nunique goals for its system, such as:",
                'card1_title' => 'Hand Tailored Solutions',
                'card1_body' => 'Design websites that are uniquely customized...',
                'card2_title' => 'Enhance Client Collaboration',
                'card2_body' => 'Integrate closely with clients throughout...',
                'card3_title' => 'Boost Business Performance',
                'card3_body' => 'Develop a maintenance and support process...',
                'card4_title' => 'Ensure Exceptional User Experience',
                'card4_body' => 'Create intuitive, visually appealing interfaces...',
                'card5_title' => 'Provide Strategic Implementation',
                'card5_body' => 'Support clients with comprehensive strategies...',
            ],
            default => ['heading' => 'New Slide'],
        };

        $slide = $this->proposal->slides()->create([
            'layout' => $layout,
            'content' => $defaults,
            'order' => $maxOrder + 1,
        ]);

        $this->proposal->load('slides');
        $newIndex = $this->proposal->slides->search(fn ($s) => $s->id === $slide->id);
        $this->loadSlide($newIndex !== false ? $newIndex : 0);
    }

    public function deleteSlide(int $index): void
    {
        if ($this->proposal->slides->count() <= 1) {
            return;
        }

        $slide = $this->proposal->slides->get($index);
        if (! $slide) {
            return;
        }

        $slide->delete();
        $this->proposal->load('slides');
        $newIndex = min($index, $this->proposal->slides->count() - 1);
        $this->loadSlide($newIndex);
    }

    public function updateTitle(): void
    {
        $this->validate(['title' => 'required|string|max:255']);
        $this->proposal->update(['title' => $this->title]);
    }

    public function updateTheme(): void
    {
        $this->proposal->update(['theme' => $this->theme]);
        $this->proposal->refresh();
    }

    public function toggleViewMode(): void
    {
        $this->viewMode = $this->viewMode === 'responsive' ? 'print' : 'responsive';
    }

    public function saveAll(): void
    {
        $this->saveCurrentSlide();
        $this->proposal->update([
            'title' => $this->title,
            'theme' => $this->theme,
        ]);

        $this->dispatch('saved');
    }

    public function themeClass(): string
    {
        return match ($this->proposal->theme) {
            'midnight' => 'bg-gradient-to-br from-gray-900 via-indigo-950 to-gray-900 text-white',
            'aurora' => 'bg-gradient-to-br from-purple-950 via-indigo-900 to-teal-950 text-white',
            'slate' => 'bg-gradient-to-br from-slate-700 via-slate-800 to-slate-900 text-white',
            'rose' => 'bg-gradient-to-br from-rose-950 via-pink-900 to-gray-900 text-white',
            'forest' => 'bg-gradient-to-br from-emerald-950 via-green-900 to-gray-900 text-white',
            default => 'bg-gray-900 text-white',
        };
    }

    public function render()
    {
        $this->proposal->load('slides');

        return view('livewire.proposal-editor')->layout('layouts.proposal-editor');
    }
}
