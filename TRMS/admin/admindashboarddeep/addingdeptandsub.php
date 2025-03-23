<!DOCTYPE html>
<html>
<head>
     <title>ADDING DEPT AND SUB</title>
     <meta charset="UTF-8 "/>
     <link rel="stylesheet" href="../adminstyle/addingdeptandsub.css" />
</head>
    <body class="teachbackground" background="#">
        <div class="wholesession">
            <div class="navigators">
                 <div class="l1">
                     <a style="text-decoration: none;" href="../../index.html"> <img src="../../logoss/trmslogo.jpg" alt="TRMS" height="150" width="150" /></a>
                 </div>          
                 <div class="n4">
                     <a style="text-decoration: none;" href="../admindashboard.php" >  <h3 id="adt5">BACK</h3></a><br>
                 </div>
                 <div class="n4">
                     <a style="text-decoration: none;" href="../teachhelp/teachhelp.html" >  <h3 id="adt5">HELP</h3></a><br>
                 </div>
            </div>    <!--navigator-->
                 <div class="mainsection">
                    
                               
                               <h1>Add New Department and Subjects</h1><br><br>
                                        <form action="../admindb/adddept.php" method="post">
                                            <label for="department">Department:</label>
                                            <input type="text" name="department" required><br><br>

                                           
                                            <label for="subjects">* Subjects (one per text area)</label><br><br>
                                           
                                                    <div id="subjectContainer">
                                                        <template id="subjectTemplate">
                                                            <textarea name="subject[]" rows="1"></textarea><br>
                                                        </template>
                                                    </div>
                                                    <button type="button" onclick="addRow()">Add More Rows</button>

                                                    <script>
                                                        function addRow() {
                                                            var container = document.getElementById("subjectContainer");
                                                            var template = document.getElementById("subjectTemplate");
                                                            var clone = document.importNode(template.content, true);
                                                            container.appendChild(clone);
                                                        }
                                                    </script>
                                           <br><br> <input type="submit" value="Add">
                                        </form>
                             
        
        </div><!--mainsection-->
      </div>                
    </body>
</html>