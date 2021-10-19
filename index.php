<!-- Database Connection -->
<?php $conn = mysqli_connect("localhost", "root", "", "test"); ?>

<!-- Start Session -->
<?php session_start(); ?>

<!-- Error & success messages -->
<p><?php if(isset($_GET['success'])): echo $_GET['success']; endif; ?></p>
<p><?php if(isset($_GET['error'])): echo $_GET['error']; endif; ?></p>

<!-- Check weather session variable has a value -->
<?php if(isset($_SESSION["email"])): ?>

    <!-- Logout -->
    <form action="" method="post">
        <button type="submit" name="Logout">Logout</button>
    </form>

    <!-- Create Form -->
    <?php if(!isset($_GET['update'])): ?>
        <form action="" method="post">
            <input type="text" name="post_title" placeholder="Post title...">
            <input type="text" name="post_description" placeholder="Post description...">
            <button type="submit" name="Create">Create Post</button>
        </form>
    <?php endif; ?>


    <!-- Update Form -->
    <?php if(isset($_GET['update'])): ?>    
        <?php
        $post_id = $_GET['update'];
        $query = mysqli_query($conn, "SELECT * FROM posts WHERE post_id = '$post_id'");
        ?>

        <?php while($row = mysqli_fetch_array($query)): ?>
            <form action="" method="post">
                <input type="text" name="post_id" value="<?php echo $row['post_id']; ?>" hidden>
                <input type="text" name="post_title" placeholder="Enter your post title" value="<?php echo $row['post_title']; ?>">
                <input type="text" name="post_description" placeholder="Enter you post description" value="<?php echo $row['post_description']; ?>">
                <button type="submit" name="Update">Update Post</button>
            </form>
        <?php endwhile; ?>
    <?php endif; ?>


    <!-- Create -->
    <?php if(isset($_POST['Create'])): ?>
        <?php
        $post_title = $_POST['post_title'];
        $post_description = $_POST['post_description'];

        if(empty($post_title) || empty($post_description)):
            header("Location: ./?error=Input fields cannot be empty");
        else:
            if(mysqli_query($conn, "INSERT INTO posts(post_title, post_description) VALUES('$post_title', '$post_description')")):
                header("Location: ./?success=Successfully created a post");
            else:
                header("Location: ./?error=Unable to create a post");
            endif;
        endif;
        ?>
    <?php endif; ?>


    <!-- Read Single Post -->
    <?php if(isset($_GET['post'])): ?>

        <?php $post_id = $_GET['post'];?>
        <?php $query = mysqli_query($conn, "SELECT * FROM posts WHERE post_id = '$post_id'"); ?>

        <?php if($row = mysqli_fetch_array($query)): ?>
            <?php echo $row['post_title']; ?><br>
            <?php echo $row['post_description']; ?><br>
            <a href="?update=<?php echo $row['post_id']; ?>">Update</a>
            <a href="?delete=<?php echo $row['post_id']; ?>">Delete</a><br><br>
        <?php endif; ?>

    <?php else: ?>

        <!-- Read All Posts-->
        <?php $query = mysqli_query($conn, "SELECT * FROM posts"); ?>

        <?php while($row = mysqli_fetch_array($query)): ?>
            <?php echo $row['post_title']; ?><br>
            <?php echo $row['post_description']; ?><br>
            <a href="?post=<?php echo $row['post_id']; ?>">View</a>
            <a href="?update=<?php echo $row['post_id']; ?>">Update</a>
            <a href="?delete=<?php echo $row['post_id']; ?>">Delete</a><br><br>
        <?php endwhile; ?>

    <?php endif; ?>


    <!-- Update -->
    <?php if(isset($_POST['Update'])): ?>
        <?php
        $post_id = $_POST['post_id'];
        $post_title = $_POST['post_title'];
        $post_description = $_POST['post_description'];

        if(empty($post_title) || empty($post_description)):
            header("Location: ./?error=Input fields cannot be empty");
        else:
            if(mysqli_query($conn, "UPDATE posts SET post_title = '$post_title', post_description = '$post_description' WHERE post_id = '$post_id'")):
                header("Location: ./?success=Successfully updated the post");
            else:
                header("Location: ./?error=Unable to update the post");
            endif;
        endif;
        ?>
    <?php endif; ?>


    <!-- Delete -->
    <?php if(isset($_GET['delete'])): ?>
        <?php
        $post_id = $_GET['delete'];

        if(mysqli_query($conn, "DELETE FROM posts WHERE post_id = '$post_id'")):
            header("Location: ./?success=Successfully deleted the post");
        else:
            header("Location: ./?error=Unable to delete the post");
        endif;
        ?>
    <?php endif; ?>


    <!-- Logout User -->
    <?php if(isset($_POST['Logout'])): ?>
        <?php
        session_start();
        session_unset();
        session_destroy();
        header("Location: ./");
        ?>
    <?php endif; ?>

<?php else: ?>

    <!-- Login Form -->
    <h1>Login</h1>
    <form action="" method="post">
        <input type="text" name="email" placeholder="Enter your email...">
        <input type="password" name="password" placeholder="Enter your password...">
        <button type="submit" name="Login">Login</button>
    </form>


    <!-- Register Form -->
    <h1>Register</h1>
    <form action="" method="post">
        <input type="text" name="email" placeholder="Enter your email...">
        <input type="text" name="username" placeholder="Enter your username...">
        <input type="number" name="phone" placeholder="Enter your phone number">
        <input type="password" name="password" placeholder="Enter your password...">
        <button type="submit" name="Register">Register</button>
    </form>


    <!-- Register User -->
    <?php if(isset($_POST['Register'])): ?>
        <?php
        $email = $_POST['email'];
        $username = $_POST['username'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];

        if(empty($email) || empty($username) || empty($phone) || empty($password)):
            header("Location: ./?error=Input fields cannot be empty");
        else:
            if(mysqli_query($conn, "INSERT INTO users(email, username, phone, password) VALUES('$email', '$username', '$phone', '$password')")):
                $_SESSION["email"] = $email;
                header("Location: ./?success=Registration successful");
            else:
                header("Location: ./?error=Registration failed");
            endif;
        endif;
        ?>
    <?php endif; ?>
    

    <!-- Login User -->
    <?php if(isset($_POST['Login'])): ?>
        <?php
        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' AND password = $password");
        if($row = mysqli_fetch_array($query)):
            $_SESSION["email"] = $row['email'];
            header("Location: ./?success=Login successful");
        else:
            header("Location: ./?success=Login failed");
        endif;
        ?>
    <?php endif; ?>

<?php endif; ?>
