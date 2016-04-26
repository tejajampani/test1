<?php
echo'<form method="post" action="proddy_services_handler.php">
name:<input type="text" name="name"></br>
Phno:<input type="text" name="phno"></br>
email:<input type="text" name="email"></br>
address:<input type="text" name="address"></br>
gender:<input type="text" name="gender"></br>
<input type="submit" name="submit" value="submit">
</form>';
echo'<a href="proddy_services_handler.php?action=getItems&name=teja&id=1">Click Here</a>';  
?> 