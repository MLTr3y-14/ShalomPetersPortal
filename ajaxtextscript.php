<html>
   <head>
       <title> You Cube- The Blog for Cube puzzlers</title>
         <script type = "text/javascript">
               //blog object constructor
               function Blog(body, date) {
                     //assign the properties
                        this.body = body;
                        this.date = date;
                           }
                      //Global array of blog entries
                       var blog = [ new Blog("Got the new cube I ordered....", "08/14/2008"), new Blog("Solved the new cube but of course...","08/19/2008"), new Blog("Managed to get a headache toiling....", "08/16/2008"), new Blog("Found a 7x7x7 cube for sale online........", "08/21/2008") ];
                 //show the list of blog entries
           function ShowBlog(numEntries) {
                       //Adjust the number of entries to show the full blog, if neccessary
                        if(!numEntries)
                              numEntries = blog.length;
                                //Show the blog entries
                                       var i = 0; 
									   var blogtext ="";
                                         while(i<blog.length && i<numEntries) {
                                  //use a grey background for every other blog entry
                                        if( i%2 ==0)
                                             blogtext += "<p style='background-color:#EEEEEE'>";
                                                  else
                                                    blogtext += "<p>";
                                            //Generate the formatted blog HTML code
                                              blogtext += "<strong>" + blog[i].date + "</strong></br>" + blog[i].body + "</p>";
                                                 i++;
                                                }
                                                 //set the blog HTML code on the page
                                                  document.getElementById("blog").innerHTML = blogtext;
                                                 }
                                                    </script>
                                                    </head>
                                                <body onload="ShowBlog(2);">
                                                    <h3> You cube- The Blog for Cube Puzzlers</h3>
                                                      <img src ="Cube.png" alt ="YouCube" />
                                                        <div id="blog"></div>
                                                            <input type="button" id="showall" value="Show All Blog Entries" onclick="ShowBlog();" />
                                                              </body>
                                                               </html>