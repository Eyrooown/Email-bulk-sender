<x-guest-layout>
    <div class="flex w-full shadow-2xl rounded-lg overflow-hidden"
    style="height: 600px;">

        <div class="hidden md:flex w-1/2 flex-col items-center justify-center p-10"
        style="background-color: #102B3C;">
            <img src="{{ asset('images/inverted-logo.png') }}" alt="Logo" class="w-40" />
        </div>


        <div class="w-full md:w-1/2 bg-white flex flex-col items-center justify-center p-10">
            <h2 class="text-2xl font-bold mb-6">Login</h2>

            <form class="items-center justify-center" method="POST" action="{{ route('login') }}" class="w-full">
                @csrf

                <input type="email" name="email" placeholder="Username"
                    class="input input-bordered w-full mb-3 rounded-lg" required />


                <input type="password" name="password" placeholder="Password"
                    class="input input-bordered w-full mb-2 rounded-lg" required />


                <div class="text-xs mb-4">
                    <a href="{{ route('password.request') }}"
                        class="text-gray-500 hover:underline">
                        Forgot password?
                    </a>
                </div>
                <div class="flex justify-center items-center">
                    <button type="submit" class="btn rounded-2xl w-40 px-8" style="background: #ED1C24; color: white;">
                        Login
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-guest-layout>
