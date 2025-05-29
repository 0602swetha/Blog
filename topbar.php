<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="topbar">
        <div class="logo">
            <img src="perennial logo1.png"class="logo-image">
        </div>
        <div class="content">
            <div class="home">
                <a href="index.php" class="">home</a>
            </div>
            <div class="about">
                <a href="about.php" class="">About</a>
            </div>

            <div class="contact">
                <a href="contact.php" class="">Contact us</a>
            </div>
        </div>




        <div class="user-profile">
            <img src="userimage.png" alt="Profile" class="profile-pic" onclick="toggleDropdown()">
            <div class="topbar-dropdown" id="profileDropdown">
            
                
                <a href="index.php">
                <span class="title">Signout</span>
                </a>
                <a href="#">
                     <span class="title">Settings</span>
                    </a>
                    
            </div>
        </div>



    </div>
    <script>
        // Wait until the document is fully loaded before running the script
        document.addEventListener('DOMContentLoaded', function () {
            // Select the dropdown menu using its ID
            const dropdown = document.getElementById("profileDropdown");
            const profilePic = document.querySelector(".profile-pic");

            // Toggle dropdown visibility
            function toggleDropdown() {
                if (dropdown) {
                    dropdown.classList.toggle("show");
                } else {
                    console.error("Dropdown element is not found.");
                }
            }

            // Close dropdown if clicked outside
            window.onclick = function(event) {
                // Close the dropdown if clicked outside of profile section
                if (dropdown && !event.target.matches('.profile-pic') && !event.target.closest('.user-profile')) {
                    dropdown.classList.remove("show");
                }
            };

            // Add the toggle functionality
            if (profilePic) {
                profilePic.addEventListener("click", toggleDropdown);
            }
        });
    </script>
  
 


</body>

</html>