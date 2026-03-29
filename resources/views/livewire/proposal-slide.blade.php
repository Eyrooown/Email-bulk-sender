<div class="flex flex-col w-full bg-gray-100 shadow-sm">
    {{-- First Page --}}
    <div class="flex w-full aspect-video bg-white shadow-sm">

        {{-- Left dark bar --}}
        <div class="w-32 clr-primary shrink-0"></div>

        {{-- Content --}}
        <div class="flex flex-col w-full h-full px-12 py-6 gap-8">

            {{-- Header --}}
            <div class="flex justify-between items-center">
                <div class="flex flex-row items-center gap-4">
                    <x-logo />
                    <div class="w-px h-12 clr-primary shrink-0"></div>
                    <p class="text-lg clr-txt-secondary">Your Partner Towards Digital Innovation</p>
                </div>
                <x-circles />
            </div>

            {{-- Body --}}
            <div class="flex flex-row flex-1 gap-8">

                {{-- Thin vertical line --}}
                <div class="w-px clr-primary shrink-0"></div>

                {{-- Title text --}}
                <div class="flex flex-col justify-center gap-2">
                    <p class="text-7xl font-bold clr-txt-primary">WEBSITE</p>
                    <p class="text-7xl font-normal clr-txt-primary">DEVELOPMENT</p>
                    <p class="text-7xl font-extralight clr-txt-secondary">PROPOSAL</p>
                </div>

                {{-- Icon --}}
                <div class="flex flex-1 justify-center items-center">
                    <img src="{{ asset('images/icon-dark.png') }}" alt="Icon" class="h-3/4 w-auto" />
                </div>

            </div>
        </div>
    </div>

    {{-- Second Page --}}
    <div class="flex w-full aspect-video bg-white shadow-sm">

        {{-- Content --}}
        <div class="flex flex-col w-full h-full px-12 py-6 gap-8">

            {{-- Header --}}
            <div class="flex justify-between items-center">
                <div class="flex flex-row items-center gap-4">
                    <p class="text-7xl clr-txt-primary">Executive Summary</p>
                </div>
                <x-circles />
            </div>
            <hr class="border-clr-primary w-2/5 border-2">

            {{-- Body --}}
            <div class="flex flex-row flex-1 gap-8">

                {{-- Title text --}}
                <div class="flex flex-row w-full justify-center items-center gap-2 overflow-hidden">
                    {{-- Left content box --}}
                    <div class="relative h-9/10 w-4/5 clr-txt-primary bg-white shadow-xl rounded-xl p-4 z-10">
                        <p>Odecci Solutions Inc., is committed to delivering innovative, user-centric digital solutions
                            that empower businesses to
                            thrive in today’s competitive landscape. This proposal outlines our comprehensive website
                            development service
                            designed to enhance your brand presence, improve customer engagement, and drive measurable
                            business growth. </p>
                        <br>
                        <p>
                            Our approach combines cutting-edge technology, intuitive design, and strategic functionality
                            to create a website that is
                            not only visually appealing but also optimized for performance, security, and scalability.
                            By leveraging modern
                            frameworks and best practices, we ensure your website becomes a powerful tool for marketing,
                            communication, and
                            conversion.
                        </p>

                        <p>Key Highlights of Our Service:</p>
                        <br>

                        <ul>
                            <li>
                                <span class="font-bold clr-txt-primary">Custom Design & Branding:</span> Tailored to
                                reflect your unique identity and values.
                            </li>
                            <li>
                                <span class="font-bold clr-txt-primary">Responsive & Mobile-First Development:</span>
                                Seamless experience across all devices.
                            </li>
                            <li>
                                <span class="font-bold clr-txt-primary">SEO & Performance Optimization:</span> Enhanced
                                visibility and faster load times.
                            </li>
                            <li>
                                <span class="font-bold clr-txt-primary">Content Management System (CMS):</span> Easy
                                updates and scalability for future growth.
                            </li>
                            <li>
                                <span class="font-bold clr-txt-primary">Security & Compliance:</span> Robust measures to
                                protect data and maintain trust.
                            </li>
                            <li>
                                <span class="font-bold clr-txt-primary">Analytics Integration:</span> Actionable
                                insights to monitor and improve performance.
                            </li>
                        </ul>
                        <br>
                        <p>
                            Partnering with Odeccimeans gaining a strategic ally focused on delivering a website that
                            aligns with your business
                            objectives, strengthens your digital footprint, and creates lasting impact. Our team ensures
                            timely delivery, transparent
                            communication, and ongoing support to maximize your investment.
                        </p>
                    </div>

                    {{-- Image overlapping --}}
                    <div class="flex flex-col relative -ml-44 justify-end">
                        <img src="{{ asset('images/executive-bg.png') }}" alt="Icon" class="h-3/4 w-auto" />
                        <div class="absolute rounded-full h-96 w-96 clr-primary z-10 -bottom-40 -right-40"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Third Page --}}
    <div class="flex w-full aspect-video bg-white shadow-sm">

        {{-- Left dark bar --}}
        <div class="w-32 clr-primary shrink-0"></div>

        {{-- Content --}}
        <div class="flex flex-col w-full h-full px-12 py-6 gap-8">

            {{-- Header --}}
            <div class="flex justify-between items-center">
                <div class="flex flex-row items-center gap-4">
                    <x-logo />
                </div>
                <div class="flex flex-col">
                    <div class="flex flex-row justify-center items-center">
                        <h1 class="text-6xl clr-txt-secondary mt-6">OUR STRATEGY</h1>
                        <x-circles />
                    </div>
                    <div class="">
                        <hr class="w-3/4 border-2 border-clr-primary mt-10">
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="flex flex-row justify-between">
                <div class="flex flex-col flex-1">
                    <h1 class="font-medium text-7xl clr-txt-primary">Who is <br>Odecci?</h1>
                    <br>
                    <p class="clr-txt-primary mt-10">
                        Odecci Solutions Inc. is a software <br>
                        development company that provides <br>
                        comprehensive software development <br>
                        services and focuses on end-to-end digital <br>
                        solutions that empower businesses to <br>
                        streamline and enhance their operations.
                    </p>
                    <br>
                    <p class="clr-txt-primary">
                        The company’s goal is to help businesses by <br>
                        providing quality and efficient digital solutions <br>
                        that enable them to excel in their industry.
                    </p>
                    <br>
                    <p>
                        Visit our website: <a href="https://odecci.com/"
                            class="underline decoration-solid">www.odecci.com</a> to learn <br>more
                    </p>
                </div>
                <div class="flex flex-1 flex-row justify-center items-center">
                    <div class="grid grid-cols-2 grid-rows-3 gap-20">
                        <div class="flex flex-row justify-center items-center gap-4">
                            <div
                                class="flex justify-center items-center h-20 w-20 shrink-0 rounded-full clr-bg-secondary text-base-100">
                                <x-icons.diamond class="w-6 h-6" />
                            </div>
                            <p class="clr-txt-secondary font-bold text-xl">Client-Centric Solutions</p>
                        </div>
                        <div class="flex flex-row justify-center items-center gap-4">
                            <div
                                class="flex justify-center items-center h-20 w-20 shrink-0 rounded-full clr-bg-secondary text-base-100">
                                <x-icons.paperplane class="w-6 h-6" />
                            </div>
                            <p class="clr-txt-secondary font-bold text-xl">Data-Driven Decision Making</p>
                        </div>
                        <div class="flex flex-row justify-center items-center gap-4">
                            <div
                                class="flex justify-center items-center h-20 w-20 shrink-0 rounded-full clr-bg-secondary text-base-100">
                                <x-icons.chart class="w-6 h-6" />
                            </div>
                            <p class="clr-txt-secondary font-bold text-xl">Agile Development</p>
                        </div>
                        <div class="flex flex-row justify-center items-center gap-4">
                            <div
                                class="flex justify-center items-center h-20 w-20 shrink-0 rounded-full clr-bg-secondary text-base-100">
                                <x-icons.calendar-check class="w-6 h-6" />
                            </div>
                            <p class="clr-txt-secondary font-bold text-xl">Sustainable Growth</p>
                        </div>
                        <div class="flex flex-row justify-center items-center gap-4">
                            <div
                                class="flex justify-center items-center h-20 w-20 shrink-0 rounded-full clr-bg-secondary text-base-100">
                                <x-icons.bulb class="w-10 h-10" />
                            </div>
                            <p class="clr-txt-secondary font-bold text-xl">Collaborative Partnership</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Fourth Page --}}
    <div class="flex w-full aspect-video bg-white shadow-sm">

        {{-- Content --}}
        <div class="flex flex-col w-full h-full px-12 py-6 gap-8">

            {{-- Header --}}
            <div class="flex justify-between items-center">
                <div class="flex flex-row items-center justify-between gap-20">
                    <div class="flex flex-col gap-10">
                        <h1 class="text-6xl font-bold clr-txt-primary">Our Strategy</h1>
                        <hr class="w-3/4 border border-clr-primary">
                    </div>
                    <p class="text-lg clr-txt-secondary">We understand that <span class="font-bold">every business
                            has<br>unique goals for its system</span>, such as: </p>
                </div>
                <x-circles />
            </div>

            {{-- Body --}}
            <div class="grid grid-cols-5 gap-8 w-full flex-1 min-w-0">
                <div class="flex flex-col min-w-0 clr-primary rounded-lg text-base-100 p-8 min-h-96 w-full">
                    <div class="flex flex-col items-center gap-4 my-auto">
                        <x-icons.bulb class="w-16 h-16 mb-2" />
                        <hr class="w-full border-2 border-white">
                        <h1 class="text-xl font-bold text-center w-full">Hand Tailored Solutions</h1>
                        <p class="text-center text-sm">Design websites that are uniquely customized to align with each
                            client's specific business needs, from branded interfaces to intricate technical
                            functionalities, ensuring a perfect fit for their operations.</p>
                    </div>
                </div>

                <div class="flex flex-col min-w-0 bg-white rounded-lg clr-txt-primary p-8 min-h-96 w-full">
                    <div class="flex flex-col items-center gap-4 my-auto">
                        <x-icons.bulb class="w-16 h-16 mb-2" />
                        <hr class="w-full border-2 border-clr-primary">
                        <h1 class="text-xl font-bold text-center w-full">Enhance Client Collaboration</h1>
                        <p class="text-center text-sm">Integrate closely with clients throughout the support process,
                            fostering a partnership that incorporates their vision and feedback to create solutions that
                            reflect their goals.</p>
                    </div>
                </div>

                <div class="flex flex-col min-w-0 clr-primary rounded-lg text-base-100 p-8 min-h-96 w-full">
                    <div class="flex flex-col items-center gap-4 my-auto">
                        <x-icons.bulb class="w-16 h-16 mb-2" />
                        <hr class="w-full border-2 border-white">
                        <h1 class="text-xl font-bold text-center w-full">Boost Business Performance</h1>
                        <p class="text-center text-sm">Develop a maintenance and support process that drives measurable
                            outcomes, such as increased website performance and improved visibility.</p>
                    </div>
                </div>

                <div class="flex flex-col min-w-0 bg-white rounded-lg clr-txt-primary p-8 min-h-96 w-full">
                    <div class="flex flex-col items-center gap-4 my-auto">
                        <x-icons.bulb class="w-16 h-16 mb-2" />
                        <hr class="w-full border-2 border-clr-primary">
                        <h1 class="text-xl font-bold text-center w-full">Ensure Exceptional User Experience</h1>
                        <p class="text-center text-sm">Create intuitive, visually appealing interfaces that enhance
                            user engagement and satisfaction, making the application both functional and accessible for
                            end-users.</p>
                    </div>
                </div>

                <div class="flex flex-col min-w-0 clr-primary rounded-lg text-base-100 p-8 min-h-96 w-full">
                    <div class="flex flex-col items-center gap-4 my-auto">
                        <x-icons.bulb class="w-16 h-16 mb-2" />
                        <hr class="w-full border-2 border-white">
                        <h1 class="text-xl font-bold text-center w-full">Provide Strategic Implementation</h1>
                        <p class="text-center text-sm">Support clients with comprehensive strategies, including case
                            studies and development roadmaps, to ensure seamless deployment and long-term success of the
                            website.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Fifth Page — Problem Statement --}}
    <div class="flex w-full aspect-video shadow-sm min-h-0">

        <div class="flex flex-col w-full h-full min-h-0 px-10 sm:px-14 py-5 gap-3">

            {{-- Header --}}
            <div class="flex justify-between items-start shrink-0 gap-4">
                <div class="flex flex-col gap-1.5">
                    <h1 class="text-4xl sm:text-5xl font-bold clr-txt-primary tracking-tight">Problem Statement</h1>
                    <hr class="w-2/5 border-t border-clr-primary">
                </div>
                <x-circles />
            </div>

            {{-- Intro --}}
            <p class="text-sm leading-relaxed clr-txt-primary max-w-5xl shrink-0">
                In today's digital-first environment, businesses face numerous obstacles that hinder their ability to
                maintain a strong online presence. From outdated designs and poor user experience to security risks
                and low search visibility, these issues can significantly impact brand credibility and growth. Odecci's
                website development services are designed to address these challenges head-on, providing innovative,
                scalable, and secure solutions that align with your business objectives.
            </p>

            @php
            $problemItems = [
            'Lack of Professional Online Presence',
            'Poor User Experience and Navigation',
            'Limited Scalability and Content Management',
            'Low Search Engine Visibility',
            'Security Vulnerabilities and Compliance Risks',
            ];
            @endphp

            {{-- Main block --}}
            <div class="flex flex-col w-full max-w-6xl mx-auto mt-2">

                {{-- Pill + dashed rails --}}
                <div class="flex items-center gap-3 w-full mb-3">
                    <div class="flex-1 border-t border-dashed border-gray-400/70"></div>
                    <div class="clr-primary text-white px-7 py-2.5 rounded-full font-bold text-[10px] sm:text-[11px] tracking-widest uppercase whitespace-nowrap shadow-sm">
                        Top 5 most common problems encountered
                    </div>
                    <div class="flex-1 border-t border-dashed border-gray-400/70"></div>
                </div>

                {{-- Cards --}}
                <div class="grid grid-cols-5 gap-2 w-full">
                    @foreach ($problemItems as $label)
                    <div class="bg-white rounded-xl px-3 py-5 flex items-start justify-center text-center shadow-md shadow-gray-300/40 h-40">
                        <p class="text-xs sm:text-sm font-bold clr-txt-primary leading-tight">{{ $label }}</p>
                    </div>
                    @endforeach
                </div>

                {{-- Bottom bar + number circles --}}
                <div class="relative w-full h-14 mt-0">
                    {{-- Colored bar --}}
                    <div class="grid grid-cols-5 gap-2 h-5 w-full mt-3">
                        @foreach (range(1, 5) as $i)
                        <div class="{{ $i % 2 === 1 ? 'clr-primary' : 'clr-bg-secondary' }}
                                    {{ $i === 1 ? 'rounded-bl-lg' : '' }}
                                    {{ $i === 5 ? 'rounded-br-lg' : '' }}">
                        </div>
                        @endforeach
                    </div>
                    {{-- Number circles overlapping --}}
                    <div class="absolute inset-0 grid grid-cols-5 gap-2 items-center justify-items-center">
                        @foreach (range(1, 5) as $n)
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-white shadow-md border border-gray-100 text-sm font-bold clr-txt-primary">
                            {{ $n }}
                        </span>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Sixth Page — Our Custom Solution --}}<div class="flex w-full aspect-video bg-white shadow-sm min-h-0">
        <div class="flex flex-1 flex-col w-full h-full min-h-0 px-10 sm:px-14 py-5 gap-3">

            {{-- Header --}}
            <div class="flex justify-between items-start shrink-0 gap-4">
                <div class="flex flex-col gap-1.5">
                    <h1 class="text-4xl sm:text-5xl font-bold clr-txt-primary tracking-tight">Our Custom Solution</h1>
                    <hr class="w-2/5 border-t border-clr-primary">
                </div>
                <x-circles />
            </div>

            {{-- Intro --}}
            <p class="text-sm leading-relaxed clr-txt-secondary max-w-5xl shrink-0">
                Odecci delivers a
                <span class="font-bold clr-txt-primary">next-generation website development service</span>
                that pairs
                <span class="font-bold clr-txt-primary">custom design</span>
                with
                <span class="font-bold clr-txt-primary">advanced technology</span>
                and
                <span class="font-bold clr-txt-primary">strategic functionality</span>
                — giving you a fast, secure, and scalable site that supports your brand and your customers at every touchpoint.
            </p>

            @php
            $customSolutionItems = [
            [
            'boxClass' => 'clr-primary',
            'iconClass' => 'text-white',
            'title' => 'Seamless User Experience',
            'desc' => 'Intuitive navigation and mobile-first design for maximum engagement.',
            ],
            [
            'boxClass' => 'clr-bg-secondary',
            'iconClass' => 'text-white',
            'title' => 'Scalable Architecture',
            'desc' => 'Built on a robust CMS for easy updates and future growth.',
            ],
            [
            'boxClass' => 'clr-bg-light',
            'iconClass' => 'clr-txt-primary',
            'title' => 'Enhanced Visibility',
            'desc' => 'Integrated SEO strategies to boost search rankings and attract organic traffic.',
            ],
            [
            'boxClass' => 'clr-bg-secondary',
            'iconClass' => 'text-white',
            'title' => 'Enterprise-Level Security',
            'desc' => 'Strong protection against cyber threats and compliance with data standards.',
            ],
            [
            'boxClass' => 'clr-primary',
            'iconClass' => 'text-white',
            'title' => 'Actionable Insights',
            'desc' => 'Analytics integration for informed decision-making and continuous improvement.',
            ],
            ];
            @endphp

            {{-- Cards --}}
            <div class="grid grid-cols-5 gap-3 sm:gap-4 w-full mt-2">
                @foreach ($customSolutionItems as $item)
                <div class="flex flex-col items-center gap-0">

                    {{-- Icon box --}}
                    <div class="{{ $item['boxClass'] }} rounded-xl flex items-center justify-center shadow-md shadow-gray-400/15 px-4 py-6 w-full">
                        <x-icons.bulb class="w-12 h-12 sm:w-14 sm:h-14 shrink-0 {{ $item['iconClass'] }}" />
                    </div>

                    {{-- Dashed connector line --}}
                    <div class="w-px h-6 border-l border-dashed border-gray-400/70"></div>

                    {{-- Title --}}
                    <h2 class="text-xs sm:text-sm font-bold clr-txt-primary text-center leading-tight px-0.5">
                        {{ $item['title'] }}
                    </h2>

                    {{-- Desc --}}
                    <p class="text-[11px] sm:text-xs clr-txt-secondary text-center leading-snug px-0.5 mt-1">
                        {{ $item['desc'] }}
                    </p>

                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>