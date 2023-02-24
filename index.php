<style>
    div{
    display:flex;
    width:95vw;
    height: 95vh;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    font-family: 'Comic Sans MS'
  }
</style>

<div>
    <h1>Singup</h1>
  <form method="post" action="register.php">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
    <button type="submit" name="register">Register</button>
  </form>
</div>