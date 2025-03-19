<!-- your form should submit to a method in the Signin controller called authenticate. The authenticate method should validate the form data, authenticate the user, and redirect to the home page if successful. If the authentication fails, the user should be redirected back to the signin page with an error message. Same also for your signup page. Here is the form code for the signin page: -->

<form action="<?= ROOT_URL ?>/signin/authenticate" method="post">
    <input type="text" name="username" value="<?= old_value('username') ?>" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Sign In</button>
</form>