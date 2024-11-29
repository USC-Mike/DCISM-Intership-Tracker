<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Boxicons for icons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Tailwind CSS file -->
    <link rel="stylesheet" href="../assets/css/output.css">
    <title>Documents Management - Coordinator</title>
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
                    <a href="C-DocsMgnt.html" class="flex items-center gap-4 text-blue-500 text-lg p-2 hover:bg-gray-200 rounded-lg">
                        <i class="bx bx-file text-lg"></i> Document Management
                    </a>
                </li>
                <li>
                    <a href="C-ReportMgnt.html" class="flex items-center gap-4 text-gray-700 text-lg p-2 hover:bg-gray-200 rounded-lg">
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
            <!-- Pending Documents Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-semibold text-gray-700">Pending Documents</h2>
                <table class="w-full mt-4 table-auto">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left text-sm text-gray-600">Student Name</th>
                            <th class="px-4 py-2 text-left text-sm text-gray-600">Document Type</th>
                            <th class="px-4 py-2 text-left text-sm text-gray-600">Submission Date</th>
                            <th class="px-4 py-2 text-left text-sm text-gray-600">Status</th>
                            <th class="px-4 py-2 text-left text-sm text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="px-4 py-2">John Doe</td>
                            <td class="px-4 py-2">Project Report</td>
                            <td class="px-4 py-2">2024-11-01</td>
                            <td class="px-4 py-2 text-yellow-500">Pending</td>
                            <td class="px-4 py-2">
                                <button class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Approve</button>
                                <button class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 ml-2">Reject</button>
                                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 ml-2" onclick="openModal()">View</button>
                            </td>
                        </tr>
                        <!-- More rows can be added dynamically -->
                    </tbody>
                </table>
            </div>

            <!-- Approved Documents Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg mt-6">
                <h2 class="text-xl font-semibold text-gray-700">Approved Documents</h2>
                <table class="w-full mt-4 table-auto">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left text-sm text-gray-600">Student Name</th>
                            <th class="px-4 py-2 text-left text-sm text-gray-600">Document Type</th>
                            <th class="px-4 py-2 text-left text-sm text-gray-600">Status</th>
                            <th class="px-4 py-2 text-left text-sm text-gray-600">Approval Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="px-4 py-2">Jane Smith</td>
                            <td class="px-4 py-2">Internship Evaluation</td>
                            <td class="px-4 py-2 text-green-500">Approved</td>
                            <td class="px-4 py-2">2024-10-25</td>
                        </tr>
                        <!-- More rows can be added dynamically -->
                    </tbody>
                </table>
            </div>

            <!-- Document Viewer Modal (hidden by default) -->
            <div id="documentViewerModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden">
                <div class="bg-white p-6 rounded-lg shadow-lg w-2/3">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Document Viewer</h2>
                    <iframe class="w-full h-96" src="" id="documentIframe" frameborder="0"></iframe>
                    <div class="mt-4 text-right">
                        <button class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600" onclick="closeModal()">Close</button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Document Modal -->
    <div id="documentModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg w-3/4 md:w-1/2">
            <h3 class="text-2xl font-semibold text-gray-700 mb-4">View Submitted Document</h3>
            <iframe class="w-full h-96 border border-gray-300" src="path_to_document.pdf"></iframe>
            <div class="flex justify-end mt-4">
                <button class="bg-gray-500 text-white py-2 px-6 rounded-md" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        // Function to open the document viewer modal
        function openDocumentViewer(url) {
            document.getElementById('documentIframe').src = url;
            document.getElementById('documentViewerModal').classList.remove('hidden');
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById('documentViewerModal').classList.add('hidden');
        }

         // Modal functionality
         function openModal() {
            document.getElementById('documentModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('documentModal').classList.add('hidden');
        }
    </script>

</body>
</html>
