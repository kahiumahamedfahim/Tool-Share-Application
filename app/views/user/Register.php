<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>

    <!-- CSS -->
    <link rel="stylesheet" href="/tool_sharing_application/public/assets/css/register.css">
</head>
<body>

<div class="container">
    <h2>Create Account</h2>

    <form action="?url=user/register"
          method="POST"
          enctype="multipart/form-data">

        <!-- Name -->
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" required>

        <!-- Email -->
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required>

        <!-- Phone -->
        <label for="phone">Phone Number</label>
        <input type="text" id="phone" name="phone" required>

        <!-- Password -->
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <!-- Confirm Password -->
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" id="confirmPassword" name="confirm_password" required>

        <!-- Show Password -->
        <div class="show-password">
            <input type="checkbox" id="showPassword">
            <label for="showPassword">Show Password</label>
        </div>

        <!-- Profile Image -->
        <label for="profile_image">Profile Picture</label>
        <input type="file" id="profile_image" name="profile_image" required>

        <!-- Role -->
        <label>Register As</label>
        <div class="radio-group">
            <label>
                <input type="radio" name="role" value="USER" checked>
                User
            </label>
            <label>
                <input type="radio" name="role" value="VENDOR">
                Vendor
            </label>
        </div>

        <!-- Vendor Fields -->
        <div id="vendorFields" class="hidden">
            <label for="shop_number">Shop Number</label>
            <input type="text" id="shop_number" name="shop_number">

            <label for="business_card_no">Business Card Number</label>
            <input type="text" id="business_card_no" name="business_card_no">
        </div>

        <!-- NID (USER only) -->
        <div id="nidField">
            <label for="nid_number">NID Number</label>
            <input type="text" id="nid_number" name="nid_number">
        </div>

        <!-- Submit -->
        <button type="submit">Register</button>
    </form>
</div>

<!-- JS -->
<script src="/tool_sharing_application/public/assets/js/register.js"></script>

</body>
</html>
