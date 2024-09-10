<?php
session_start();
require_once '../config.php';

// Fungsi untuk membersihkan input
function clean_input($data)
{
    global $pdo;
    return htmlspecialchars(strip_tags(trim($data)));
}

// Fungsi untuk upload gambar
function uploadImage($file)
{
    $target_dir = "../assets/img/";
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_file_name = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_file_name;

    $check = getimagesize($file["tmp_name"]);
    if ($check === false) return false;
    if ($file["size"] > 5000000) return false;
    if (!in_array($file_extension, ["jpg", "jpeg", "png", "gif"])) return false;

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $new_file_name;
    } else {
        return false;
    }
}

// Proses form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'create') {
            $title = clean_input($_POST['title']);
            $icon = clean_input($_POST['icon']);
            $description = clean_input($_POST['description']);

            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploaded_image = uploadImage($_FILES['image']);
                if ($uploaded_image) {
                    $image = $uploaded_image;
                }
            }

            $stmt = $pdo->prepare("INSERT INTO skills (title, icon, image, description) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([$title, $icon, $image, $description]);
            $message = "Skill added successfully.";

            if ($result) {
                $_SESSION['message'] = $message;
            } else {
                $_SESSION['error'] = "Error: " . implode(", ", $stmt->errorInfo());
            }
        } elseif ($action == 'update') {
            $title = clean_input($_POST['title']);
            $icon = clean_input($_POST['icon']);
            $description = clean_input($_POST['description']);
            $id = clean_input($_POST['id']);

            // Check if the user wants to delete the existing image
            $delete_image = isset($_POST['delete_image']) && $_POST['delete_image'] == '1';

            // Fetch the current image filename
            $stmt = $pdo->prepare("SELECT image FROM skills WHERE id = ?");
            $stmt->execute([$id]);
            $current_image = $stmt->fetchColumn();

            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploaded_image = uploadImage($_FILES['image']);
                if ($uploaded_image) {
                    $image = $uploaded_image;
                    // Delete the old image file if it exists
                    if ($current_image && file_exists("../assets/img/" . $current_image)) {
                        unlink("../assets/img/" . $current_image);
                    }
                }
            } elseif ($delete_image && $current_image) {
                // Delete the image file if delete_image is checked
                if (file_exists("../assets/img/" . $current_image)) {
                    unlink("../assets/img/" . $current_image);
                }
                $image = ''; // Set image to empty string to update database
            } else {
                $image = $current_image; // Keep the current image
            }

            $stmt = $pdo->prepare("UPDATE skills SET title=?, icon=?, image=?, description=? WHERE id=?");
            $result = $stmt->execute([$title, $icon, $image, $description, $id]);
            $message = "Skill updated successfully.";

            if ($result) {
                $_SESSION['message'] = $message;
            } else {
                $_SESSION['error'] = "Error: " . implode(", ", $stmt->errorInfo());
            }
        } elseif ($action == 'delete') {
            $id = clean_input($_POST['id']);

            // Fetch the current image filename
            $stmt = $pdo->prepare("SELECT image FROM skills WHERE id = ?");
            $stmt->execute([$id]);
            $current_image = $stmt->fetchColumn();

            // Delete the image file if it exists
            if ($current_image && file_exists("../assets/img/" . $current_image)) {
                unlink("../assets/img/" . $current_image);
            }

            $stmt = $pdo->prepare("DELETE FROM skills WHERE id=?");
            if ($stmt->execute([$id])) {
                $_SESSION['message'] = "Skill deleted successfully.";
            } else {
                $_SESSION['error'] = "Error deleting skill.";
            }
        }
    }

    header("Location: manage_skills.php");
    exit();
}

// Fetch all skills
$stmt = $pdo->query("SELECT * FROM skills ORDER BY id ASC");
$skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Skills</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100" x-data="skillManager()">
    <?php include('../admin/components/navbar.php'); ?>

    <div class="container mx-auto p-6">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo $_SESSION['message']; ?></span>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>



        <!-- Skills Table -->
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="container mx-auto p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">Manage Skills</h1>
                    <button @click="openAddModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Add New Skill
                    </button>
                </div>

                <!-- Existing code for messages... -->
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Title</th>
                            <th class="px-4 py-2">Icon</th>
                            <th class="px-4 py-2">Image</th>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($skills as $skill): ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($skill['title']); ?></td>
                                <td class="border px-4 py-2"><i class="<?php echo htmlspecialchars($skill['icon']); ?>"></i> <?php echo htmlspecialchars($skill['icon']); ?></td>
                                <td class="border px-4 py-2">
                                    <?php if ($skill['image']): ?>
                                        <img src="../assets/img/<?php echo htmlspecialchars($skill['image']); ?>" alt="<?php echo htmlspecialchars($skill['title']); ?>" class="w-24 h-24 object-cover">
                                    <?php else: ?>
                                        No image
                                    <?php endif; ?>
                                </td>
                                <td class="border px-4 py-2">
                                    <?php
                                    $description = htmlspecialchars($skill['description']);
                                    echo (strlen($description) > 50) ? substr($description, 0, 50) . '...' : $description;
                                    ?>
                                </td>
                                <td class="border px-4 py-2">
                                    <button @click="editSkill(<?php echo htmlspecialchars(json_encode($skill)); ?>)" class="text-green-500 underline font-semibold py-2 px-4 rounded mr-2">
                                        Edit
                                    </button>
                                    <form action="manage_skills.php" method="POST" class="inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $skill['id']; ?>">
                                        <button type="submit" class="text-red-500 underline font-semibold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this skill?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Edit Modal -->
            <div x-show="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Skill</h3>
                        <form action="manage_skills.php" method="POST" enctype="multipart/form-data" class="mt-2">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" x-model="editingSkill.id">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-title">Title</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="edit-title" type="text" name="title" x-model="editingSkill.title" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-icon">Icon</label>
                                <div class="flex">
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="edit-icon" type="text" name="icon" x-model="editingSkill.icon" required readonly>
                                    <button type="button" @click="showIconPopup = true" class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                        Choose Icon
                                    </button>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-image">New Image (optional)</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="edit-image" type="file" name="image" accept="image/*">
                            </div>
                            <div class="mb-4" x-show="editingSkill.image">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="delete_image" value="1" class="form-checkbox">
                                    <span class="ml-2">Delete existing image</span>
                                </label>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-description">Description</label>
                                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="edit-description" name="description" x-model="editingSkill.description" required></textarea>
                            </div>
                            <div class="flex items-center justify-between">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Skill</button>
                                <button type="button" @click="showModal = false" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Add Skill Modal -->
            <div x-show="showAddModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Skill</h3>
                        <form action="manage_skills.php" method="POST" enctype="multipart/form-data" class="mt-2">
                            <input type="hidden" name="action" value="create">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="add-title">Title</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="add-title" type="text" name="title" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="add-icon">Icon</label>
                                <div class="flex">
                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="add-icon" type="text" name="icon" x-model="selectedIcon" required readonly>
                                    <button type="button" @click="showIconPopup = true" class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                        Choose Icon
                                    </button>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="add-image">Image</label>
                                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="add-image" type="file" name="image" accept="image/*">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="add-description">Description</label>
                                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="add-description" name="description" required></textarea>
                            </div>
                            <div class="flex items-center justify-between">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Skill</button>
                                <button type="button" @click="showAddModal = false" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Icon Popup -->
            <div x-show="showIconPopup" class="fixed inset-0 overflow-y-auto z-50" x-cloak>
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showIconPopup = false">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        Choose an Icon
                                    </h3>
                                    <div class="mt-2">
                                        <input type="text" x-model="searchTerm" @input="filterIcons()" placeholder="Search icons..." class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <div class="grid grid-cols-6 gap-2 mt-4 max-h-60 overflow-y-auto">
                                            <template x-for="icon in filteredIcons" :key="icon">
                                                <div @click="selectIcon(icon)" class="flex items-center justify-center h-10 border border-gray-300 rounded cursor-pointer hover:bg-gray-100">
                                                    <i :class="icon + ' text-xl'"></i>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" @click="showIconPopup = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function skillManager() {
            return {
                showModal: false,
                showIconPopup: false,
                showAddModal: false,
                editingSkill: {},
                selectedIcon: '',
                searchTerm: '',
                icons: [
                    'ri-home-line', 'ri-user-line', 'ri-settings-line', 'ri-mail-line',
                    'ri-calendar-line', 'ri-file-list-line', 'ri-search-line', 'ri-heart-line',
                    'ri-star-line', 'ri-message-2-line', 'ri-image-line', 'ri-video-line',
                    'ri-music-2-line', 'ri-map-pin-line', 'ri-bookmark-line', 'ri-attachment-line',
                    'ri-link-m', 'ri-chat-1-line', 'ri-phone-line', 'ri-camera-line',
                    'ri-book-open-line', 'ri-graduation-cap-line', 'ri-pencil-ruler-2-line', 'ri-school-line',
                    'ri-award-line', 'ri-computer-line', 'ri-code-s-slash-line', 'ri-database-2-line',
                    'ri-cloud-line', 'ri-facebook-circle-line', 'ri-instagram-line', 'ri-twitter-line',
                    'ri-youtube-line', 'ri-file-text-line', 'ri-file-pdf-line', 'ri-gallery-line', 'ri-shake-hands-line'
                ],
                filteredIcons: [],
                init() {
                    this.filteredIcons = this.icons;
                },
                filterIcons() {
                    this.filteredIcons = this.icons.filter(icon =>
                        icon.toLowerCase().includes(this.searchTerm.toLowerCase())
                    );
                },
                editSkill(skill) {
                    this.editingSkill = {
                        ...skill
                    };
                    this.selectedIcon = skill.icon;
                    this.showModal = true;
                },
                selectIcon(icon) {
                    this.selectedIcon = icon;
                    this.editingSkill.icon = icon;
                    this.showIconPopup = false;
                },
                openIconPopup() {
                    this.showIconPopup = true;
                    this.filterIcons();
                },
                openAddModal() {
                    this.showAddModal = true;
                    this.selectedIcon = '';
                },
            }
        }
    </script>
</body>

</html>