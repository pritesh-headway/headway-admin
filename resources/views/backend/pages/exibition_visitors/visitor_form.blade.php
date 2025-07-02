<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customer Enquiry Form - Headway</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind CDN (for quick use, use npm in production) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #f97316;
            /* box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.4); */
            /* custom orange ring */
        }

        input[type="radio"] {
            accent-color: #f97316;
            border: none;
            outline: none;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center py-10 px-4">

    <div class="w-full max-w-2xl bg-white shadow-lg rounded-lg p-8">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-[#f97316]">Customer Enquiry Data</h2>
            <p class="text-sm text-gray-900">Headway Business Solutions LLP</p>
        </div>


        @if (session('success'))
            <div
                class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-md text-center font-medium">
                {{ session('success') }}
            </div>
        @endif


        <form action="{{ route('exibition_visitors.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-gray-700 font-medium">Event Venue</label>
                <input type="text" name="event_venue" class="w-full mt-1 px-4 py-2 border rounded-md " />
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Jeweller's Name <span
                        class="text-red-500">*</span></label>
                <input type="text" name="jeweller_name" required class="w-full mt-1 px-4 py-2 border rounded-md " />
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Owner's Name <span class="text-red-500">*</span></label>
                <input type="text" name="owner_name" required class="w-full mt-1 px-4 py-2 border rounded-md " />
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Email</label>
                <input type="email" name="email" class="w-full mt-1 px-4 py-2 border rounded-md " />
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Mobile Number (1) <span
                        class="text-red-500">*</span></label>
                <input type="text" name="mobile_1" required class="w-full mt-1 px-4 py-2 border rounded-md " />
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Mobile Number (2)</label>
                <input type="text" name="mobile_2" class="w-full mt-1 px-4 py-2 border rounded-md " />
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Address <span class="text-red-500">*</span></label>
                <textarea name="address" rows="2" required class="w-full mt-1 px-4 py-2 border rounded-md "></textarea>
            </div>

            <div>
                <label class="block text-gray-700 font-medium">City <span class="text-red-500">*</span></label>
                <input type="text" name="city" required class="w-full mt-1 px-4 py-2 border rounded-md " />
            </div>

            {{-- <div>
                <label class="block text-gray-700 font-medium">Enquired For <span class="text-red-500">*</span></label>
                <input type="text" name="enquired_for" required
                    class="w-full mt-1 px-4 py-2 border rounded-md " />
            </div> --}}

            <div>
                <label class="block text-gray-700 font-medium mb-2">Enquired For <span
                        class="text-red-500">*</span></label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="enquired_for" value="Headway Service" class="mr-2" required>
                        <span>Headway Service</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="enquired_for" value="IT Service" class="mr-2" required>
                        <span>IT Service</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="enquired_for" value="SSU Memberships" class="mr-2" required>
                        <span>SSU Memberships</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="enquired_for" value="Others" class="mr-2" required>
                        <span>Others</span>
                    </label>
                </div>
            </div>


            <div>
                <label class="block text-gray-700 font-medium">Remarks</label>
                <textarea name="remarks" rows="2" class="w-full mt-1 px-4 py-2 border rounded-md "></textarea>
            </div>

            <div class="text-center">
                <button type="submit"
                    class="px-6 py-2 bg-[#f97316] text-white font-semibold rounded-md hover:bg-[#f97316] transition">
                    Submit Enquiry
                </button>
            </div>
        </form>
    </div>



</body>

</html>
