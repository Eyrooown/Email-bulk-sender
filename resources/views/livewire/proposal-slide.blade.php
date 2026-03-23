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
                        <p>Odecci Solutions Inc., is committed to delivering innovative, user-centric digital solutions that empower businesses to
                        thrive in today’s competitive landscape. This proposal outlines our comprehensive website development service
                        designed to enhance your brand presence, improve customer engagement, and drive measurable business growth. </p>
                        <br>
                        <p>
                            Our approach combines cutting-edge technology, intuitive design, and strategic functionality to create a website that is
                            not only visually appealing but also optimized for performance, security, and scalability. By leveraging modern
                            frameworks and best practices, we ensure your website becomes a powerful tool for marketing, communication, and
                            conversion.
                        </p>

                        <p>Key Highlights of Our Service:</p>
                        <br>

                        <ul>
                            <li>
                                <span class="font-bold clr-txt-primary">Custom Design & Branding:</span> Tailored to reflect your unique identity and values.
                            </li>
                            <li>
                                <span class="font-bold clr-txt-primary">Responsive & Mobile-First Development:</span> Seamless experience across all devices.
                            </li>
                            <li>
                                <span class="font-bold clr-txt-primary">SEO & Performance Optimization:</span> Enhanced visibility and faster load times.
                            </li>
                            <li>
                                <span class="font-bold clr-txt-primary">Content Management System (CMS):</span> Easy updates and scalability for future growth.
                            </li>
                            <li>
                                <span class="font-bold clr-txt-primary">Security & Compliance:</span> Robust measures to protect data and maintain trust.
                            </li>
                            <li>
                                <span class="font-bold clr-txt-primary">Analytics Integration:</span> Actionable insights to monitor and improve performance.
                            </li>
                        </ul>
                        <br>
                        <p>
                            Partnering with Odeccimeans gaining a strategic ally focused on delivering a website that aligns with your business
                            objectives, strengthens your digital footprint, and creates lasting impact. Our team ensures timely delivery, transparent
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
