<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Candidate Registration Form - Headway</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            <h2 class="text-3xl font-bold text-[#f97316]">Candidate Registration Form</h2>
            <p class="text-sm text-gray-900">Headway Business Solutions LLP</p>
        </div>

        @if (session('success'))
            <div
                class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-md text-center font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-700 rounded-md">
                <ul class="list-disc pl-5 space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('candidate.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-gray-700 font-medium">Applying For <span class="text-red-500">*</span></label>
                <input type="text" name="applying_for" required class="w-full mt-1 px-4 py-2 border rounded-md ">
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Referred By</label>
                <input type="text" name="referred_by" class="w-full mt-1 px-4 py-2 border rounded-md ">
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="w-full mt-1 px-4 py-2 border rounded-md ">
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Father's / Husband's Name <span
                        class="text-red-500">*</span></label>
                <input type="text" name="father_or_husband_name" required
                    class="w-full mt-1 px-4 py-2 border rounded-md ">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium">Mobile 1 <span class="text-red-500">*</span></label>
                    <input type="text" name="mobile_1" required class="w-full mt-1 px-4 py-2 border rounded-md ">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Mobile 2</label>
                    <input type="text" name="mobile_2" class="w-full mt-1 px-4 py-2 border rounded-md ">
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" required class="w-full mt-1 px-4 py-2 border rounded-md ">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium">Date of Birth <span
                            class="text-red-500">*</span></label>
                    <input type="date" name="dob" required class="w-full mt-1 px-4 py-2 border rounded-md ">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Gender <span class="text-red-500">*</span></label>
                    <select name="gender" required class="w-full mt-1 px-4 py-2 border rounded-md ">
                        <option value="">Choose</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Marital Status <span
                        class="text-red-500">*</span></label>
                <select name="marital_status" required class="w-full mt-1 px-4 py-2 border rounded-md ">
                    <option value="">Choose</option>
                    <option>Unmarried</option>
                    <option>Engaged</option>
                    <option>Married</option>
                    <option>Divorce</option>
                    <option>NA</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Educational Details <span
                        class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach (['SSC', 'HSC', 'GRADUATION', 'MASTER', 'ART', 'COMMERCE', 'SCIENCE', 'ENGINEERING', 'MBA', 'OTHERS'] as $option)
                        <label class="flex items-center">
                            <input type="radio" name="education" value="{{ $option }}" class="mr-2" required>
                            <span>{{ $option }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium">Job Experience <span
                            class="text-red-500">*</span></label>
                    <select name="job_experience" required class="w-full mt-1 px-4 py-2 border rounded-md ">
                        <option value="">Choose</option>
                        <option>One Year</option>
                        <option>Two Years</option>
                        <option>Three Years</option>
                        <option>Four Years</option>
                        <option>Five Years</option>
                        <option>More Than Five Years</option>
                        <option>Fresher</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Resident Type <span
                            class="text-red-500">*</span></label>
                    <select name="resident_type" required class="w-full mt-1 px-4 py-2 border rounded-md ">
                        <option value="">Choose</option>
                        <option>Family owned</option>
                        <option>Ownership</option>
                        <option>Rented</option>
                        <option>With Relatives</option>
                        <option>P.G.Hostel</option>
                        <option>Not Mentioned</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-gray-700 font-medium">Traveling Mode <span
                        class="text-red-500">*</span></label>
                <select name="traveling_mode" required class="w-full mt-1 px-4 py-2 border rounded-md ">
                    <option value="">Choose</option>
                    <option value="Locally">Locally</option>
                    <option value="Up-down">Up-down</option>
                </select>
            </div>


            <div>
                <label class="block text-gray-700 font-medium">Landmark <span class="text-red-500">*</span></label>
                <input type="text" name="landmark" required class="w-full mt-1 px-4 py-2 border rounded-md ">
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Address 1 <span class="text-red-500">*</span></label>
                <input type="text" name="address_1" required class="w-full mt-1 px-4 py-2 border rounded-md ">
            </div>

            <div>
                <label class="block text-gray-700 font-medium">Address 2</label>
                <input type="text" name="address_2" class="w-full mt-1 px-4 py-2 border rounded-md ">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium">City <span class="text-red-500">*</span></label>
                    <input type="text" name="city" required class="w-full mt-1 px-4 py-2 border rounded-md ">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Pin Code <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="pin_code" required class="w-full mt-1 px-4 py-2 border rounded-md ">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium">Last Company</label>
                    <input type="text" name="last_company" class="w-full mt-1 px-4 py-2 border rounded-md ">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Last Designation</label>
                    <input type="text" name="last_designation" class="w-full mt-1 px-4 py-2 border rounded-md ">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Last Salary</label>
                    <input type="text" name="last_salary" class="w-full mt-1 px-4 py-2 border rounded-md ">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium">Expected Salary</label>
                    <input type="text" name="expected_salary" class="w-full mt-1 px-4 py-2 border rounded-md ">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Remarks</label>
                    <textarea name="remarks" rows="2" class="w-full mt-1 px-4 py-2 border rounded-md "></textarea>
                </div>
            </div>

            <div class="text-center pt-4">
                <button type="submit"
                    class="px-6 py-2 bg-[#f97316] text-white font-semibold rounded-md hover:bg-blue-700 transition">
                    Submit Registration
                </button>
            </div>
        </form>
    </div>

</body>

</html>
