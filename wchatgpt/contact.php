<?php include('header.php'); ?>

<div class="contact-page">
    <h2>Contact Us</h2>
    <form action="submit_contact.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone">
        
        <label for="comments">Comments:</label>
        <textarea id="comments" name="comments" required></textarea>
        
        <button type="submit" class="btn">Submit</button>
    </form>
</div>

<?php include('footer.php'); ?>
