<?php
include('template/header.php');
?>
<style>
    .admin-container {
        text-align: center;
        margin: 50px auto;
        
    }
    .admin-container p {
        font-weight: bold;
        font-size: 24px;
    }
    .admin-container img {
        display: block;
        margin: 0 auto;
        width: 300px;
        height: 300px;
    }
    .info-box {
        border: 2px solid #007bff;
        padding: 20px;
        margin: 20px auto;
        width: 80%;
        max-width: 600px;
        text-align: center;
    }
    .email-link {
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: #007bff;
    }
    .email-link svg {
        margin-right: 8px;
        width: 24px;
        height: 24px;
    }
</style>

<div class="alert alert-primary m-3">
<?php if($user_type === "pacient") : ?>
    <div class="admin-container ">
        <p>Bine ați venit <?php echo $last_name . ' ' . $first_name; ?>!<br></p>
        <img src="assets/login.png" alt="Admin Image">
    </div>
    <div class="info-box">
            <h3>Assistance with Your Account </h3>
            <p>
                    Contact us: 
                    <a href="mailto:HMS@gmail.com" class="email-link">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12.713l-11.714-8.713h23.428z"/><path d="M12 13.287l-12-8.713v15.426h24v-15.426z"/></svg>
                        HMS@gmail.com
                    </a>
            </p>
        </div>
<?php elseif($user_type === "cadru_medical") : ?>
    <div class="admin-container ">
        <p>Bine ați venit Dr. <?php echo $last_name . ' ' . $first_name; ?>!<br></p><br>
        <p>Specializare: <?php echo $speciality; ?></p>
        <img src="assets/login.png" alt="Admin Image">
    </div>
<?php elseif($user_type === "admin") : ?>
    <div class="admin-container ">
            <p>Bine ați venit <?php echo $username; ?>!</p>
            <img src="assets/admin.png" alt="Admin Image">
    </div>
<?php else : ?>

<?php endif; ?>
        
</div>

<?php
include('template/footer.php');
?>