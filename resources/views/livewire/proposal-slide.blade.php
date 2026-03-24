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
            <div class="flex flex-row flex-1 gap-8">
                <div class="flex flex-col flex-1 clr-primary rounded-lg text-base-100 p-8 min-h-96">
                    <div class="flex flex-col items-center gap-4 my-auto">
                        <x-icons.bulb class="w-16 h-16 mb-2" />
                        <hr class="w-full border-2 border-white">
                        <h1 class="text-xl font-bold text-center w-full">Hand Tailored Solutions</h1>
                        <p class="text-center text-sm">Design websites that are uniquely customized to align with each
                            client's specific business needs, from branded interfaces to intricate technical
                            functionalities, ensuring a perfect fit for their operations.</p>
                    </div>
                </div>

                <div class="flex flex-col flex-1 bg-white rounded-lg clr-txt-primary p-8 min-h-96">
                    <div class="flex flex-col items-center gap-4 my-auto">
                        <x-icons.bulb class="w-16 h-16 mb-2" />
                        <hr class="w-full border-2 border-clr-primary">
                        <h1 class="text-xl font-bold text-center w-full">Enhance Client Collaboration</h1>
                        <p class="text-center text-sm">Integrate closely with clients throughout the support process,
                            fostering a partnership that incorporates their vision and feedback to create solutions that
                            reflect their goals.</p>
                    </div>
                </div>

                <div class="flex flex-col flex-1 clr-primary rounded-lg text-base-100 p-8 min-h-96">
                    <div class="flex flex-col items-center gap-4 my-auto">
                        <x-icons.bulb class="w-16 h-16 mb-2" />
                        <hr class="w-full border-2 border-white">
                        <h1 class="text-xl font-bold text-center w-full">Boost Business Performance</h1>
                        <p class="text-center text-sm">Develop a maintenance and support process that drives measurable
                            outcomes, such as increased website performance and improved visibility.</p>
                    </div>
                </div>

                <div class="flex flex-col flex-1 bg-white rounded-lg clr-txt-primary p-8 min-h-96">
                    <div class="flex flex-col items-center gap-4 my-auto">
                        <x-icons.bulb class="w-16 h-16 mb-2" />
                        <hr class="w-full border-2 border-clr-primary">
                        <h1 class="text-xl font-bold text-center w-full">Ensure Exceptional User Experience</h1>
                        <p class="text-center text-sm">Create intuitive, visually appealing interfaces that enhance
                            user engagement and satisfaction, making the application both functional and accessible for
                            end-users.</p>
                    </div>
                </div>

                <div class="flex flex-col flex-1 clr-primary rounded-lg text-base-100 p-8 min-h-96">
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
</div>
