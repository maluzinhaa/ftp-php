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
  <h1>Login</h1>
  <form method="post" action="login.php">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
  </form>
</div>