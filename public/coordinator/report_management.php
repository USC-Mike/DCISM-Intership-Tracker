<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Boxicons for icons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Tailwind CSS file -->
    <link rel="stylesheet" href="../assets/css/output.css">
    <title>Progress Tracker - Reports Management</title>
</head>
<body class="bg-gray-100 font-lato">

    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="flex items-center justify-between px-6 py-4">
            <div class="flex items-center gap-4">
                <img src="./images/ces-logo.png" alt="Logo" class="h-10 w-auto">
                <h1 class="text-2xl font-semibold text-blue-500">OJT Tracker</h1>
            </div>
            <div class="flex items-center gap-6">
                <div class="relative">
                    <i class="bx bx-bell text-2xl text-gray-700 cursor-pointer"></i>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">5</span>
                </div>
                <div class="relative group">
                    <img src="./profile.jpg" alt="Profile" class="w-10 h-10 rounded-full cursor-pointer">
                    <div class="absolute right-0 mt-2 bg-white border rounded-md shadow-md hidden group-hover:block">
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">View Profile</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex flex-col md:flex-row p-6">
        <!-- Sidebar -->
        <aside class="bg-white w-full md:w-1/4 lg:w-1/5 h-auto md:h-full p-4 rounded-lg shadow-lg">
            <ul class="space-y-4">
                <li>
                    <a href="C-Dash.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-home text-lg"></i> Home
                    </a>
                </li>
                <li>
                    <a href="C-StudMgnt.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-user text-lg"></i> Student Management
                    </a>
                </li>
                <li>
                    <a href="C-DocsMgnt.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-file text-lg"></i> Document Management
                    </a>
                </li>
                <li>
                    <a href="C-ReportMgnt.html" class="flex items-center gap-4 text-blue-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-task text-lg"></i> Report Management
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-edit text-lg"></i> Evaluation Management
                    </a>
                </li>
                <li>
                    <a href="C-Profile.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-user text-lg"></i> Profile
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-4 text-red-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-log-out text-lg"></i> Logout
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Dashboard Content -->
        <section class="flex-1 md:ml-6 space-y-6">
            <!-- Report Filters -->
            <div class="flex justify-between items-center pb-4">
                <h2 class="text-xl font-semibold text-gray-700">Submitted Reports</h2>
                <div class="flex gap-4">
                    <input type="text" placeholder="Search by student" class="p-2 border rounded-md">
                    <input type="date" class="p-2 border rounded-md">
                    <input type="date" class="p-2 border rounded-md">
                    <select class="p-2 border rounded-md">
                        <option value="">Status</option>
                        <option value="Reviewed">Reviewed</option>
                        <option value="Not Reviewed">Not Reviewed</option>
                    </select>
                </div>
            </div>

            <!-- Reports Table -->
            <div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">Student Name</th>
                            <th class="px-4 py-2 text-left">Date</th>
                            <th class="px-4 py-2 text-left">Hours Worked</th>
                            <th class="px-4 py-2 text-left">Task Summary</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-2">John Doe</td>
                            <td class="px-4 py-2">2024-11-10</td>
                            <td class="px-4 py-2">8 hours</td>
                            <td class="px-4 py-2">Developed an OJT project</td>
                            <td class="px-4 py-2">For Review</td>
                            <td class="px-4 py-2">
                                <button onclick="openModal()" class="px-4 py-2 bg-blue-500 text-white rounded-md">View Details</button>
                            </td>
                        </tr>
                        <!-- More rows as needed -->
                    </tbody>
                </table>
            </div>

        </section>
    </main>

    <!-- Modal for Report Review -->
    <div id="reportModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white shadow-md rounded-lg p-6 w-3/4 md:w-1/2 lg:w-1/3">
            <h2 class="text-xl font-semibold text-gray-700">Report Review</h2>
            <div class="mt-4">
                <p><strong>Student Name:</strong> John Doe</p>
                <p><strong>Date:</strong> 2024-11-10</p>
                <p><strong>Hours Worked:</strong> 8 hours</p>
                <p><strong>Task Summary:</strong> Developed an OJT project</p>
                <p><strong>Status:</strong> Reviewed</p>
                <p class="mt-4"><strong>Notes:</strong> Excellent work on the OJT project. Keep it up!</p>
            </div>
            <div class="mt-4 flex gap-4">
                <button class="px-6 py-2 bg-green-500 text-white rounded-md">Approve</button>
                <button class="px-6 py-2 bg-blue-500 text-white rounded-md">Add Notes</button>
            </div>
            <div class="mt-4 text-right">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md">Close</button>
            </div>
        </div>
    </div>
    
    <script>
        function openModal() {
            document.getElementById("reportModal").classList.remove("hidden");
        }

        function closeModal() {
            document.getElementById("reportModal").classList.add("hidden");
        }
    </script>

    <!-- Footer (Same as Dashboard) -->
    <footer class="bg-white shadow-md mt-10">
        <div class="flex justify-between px-6 py-4">
            <p class="text-gray-700">© 2024 OJT Tracker. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="#" class="text-gray-700">Privacy Policy</a>
                <a href="#" class="text-gray-700">Terms of Service</a>
            </div>
        </div>
    </footer>
</body>
</html>
