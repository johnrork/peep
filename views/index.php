<html>
<head>
    <title>Hi there.</title>
</head>
<body>
    <h1>Welcome to Peep</h1>
    <strong>Peep is a lightweight web application framework. It was designed as a gentle introduction to php and coding in an MVC pattern. It was also a learning experience created in a weekend, and should not be considered stable or production-ready.</strong>

    <h2>Setup</h2>
    <p>Peep comes in a directory named /peep, but you can change it to whatever you want. Drop it in your web root and point your browser at it.</p>
    <p>The only files required for Peep are the <strong>.htaccess</strong> file, the <strong>index.php</strong> file, and the contents of the <strong>/base</strong> directory (although it anticipates the creaton of your <strong>controllers.php</strong> file in the app root directory)</p>
    
    <h1>What's in the tin?</h1>
    <h2>Models</h2>

    <h3>Schema Definition</h3>
    <p>Peep currently only works on pre-existing models, and assumes all tables will have an auto-incrementing primary key named <strong>id</strong>.</p>
    <p>That being said, this is all you need to do to create a model:</p>

    <pre>
    <? echo htmlspecialchars("<?php")?>


    include("base/models.php");

    class User extends Model {
        public $table = "Users";
        public $columns = array("id", "username", "first_name", "last_name", "email");
    }

    ?>
    </pre>
    <h3>ORM</h3>
    You can create a database object in three different ways
    <p>Array (if you provide <em>all</em> the values)</p>
    <pre>
    $u = new User($values=array(
        'johnrork',
        'john',
        'rork',
        'jnrork@gmail.com')
    );
    </pre>
    <p>associative array</p>
    <pre>
    $u = new User($values=array(
        'username' => 'johnrork',
        'first_name' => 'john',
        'last_name' => 'rork',
        'email' => 'jnrork@gmail.com')
    );
    </pre>
    <p>or object</p>
    <pre>
    $u = new User();

    $u->username = 'johnrork';
    $u->first_name = 'john';
    $u->last_name = 'rork';
    $u->email = 'jnrork@gmail.com';
     </pre>

     And save it simply:
     <pre>
    $u->create();
     </pre>

     Load an existing object with a primary key:
     <pre>
    $user = new User()
    $user->get(1); //returns object
     </pre>

     Change it if you wanna:
      <pre>
     $user->first_name = "Dana"
     $user->update();
      </pre>

     You can also get a whole bunch at once:
      <pre>
     $user = new User()
     $user->all(); //returns array of objects
      </pre>

      But you probably want to get more specific
       <pre>
    $user = new User()
    $user->filter(array('first_name' => 'john',
                        'last_name'  => 'rork'))->all());

    // for now it only does "=" comparisons...
       </pre>


       It does some other cool things, too, that you can mix and match
        <pre>
    $user = new User()
    $user->
        order(array('username', 'id'=>'desc'))->
        limit(5)->
        offset(2)->
        all();
        </pre>

        Just don't forget to clean up after yourself
      <pre>
     $user->delete();
      </pre>

      <h2>Views</h2>
      <p>Views are simply individual php files that live in the <strong>views/</strong> directory.
        </p>
        <p>If you, like this developer, consider views in a web-based MVC flow to be the user-facing templates, you'll want to
         these to mostly look like html, and to render them from your controllers with the <strong>render_template()</strong> method. </p>

      <h2>Controllers</h2>
        <p>The controller is where you connect your data to the user's template. Peep will automatically route urls to your controllers.</p>

        <p>If you want some action to happen at <strong>/peep/foo</strong>, simply write a controller named <strong>Foo</strong>, with at least one method named <strong>index().</strong></p>

        <p>Right now, controllers don't do much other than allow you to run arbitrary data-munging code and then render clean templates.</p>


        <p><strong>render_template()</strong> takes two arguments, the name of the template file (the views directory is added in for you), and an optional array of $data.</p>

        <p>Be aware that the method extracts the data keys from the array and presents them to the template as standard variables. Because I don't like square brackets in my templates.</p>
        <pre>
    # controllers.php
    <? echo htmlspecialchars('<?php')?>


    include('base/controllers.php');
    class MyController extends Controller{
        def index($path, $params){
            // path is the request path
            // params is a nice array built from any GET parameters present
            $this->render_template('mytemplate.php', 
                                    $data = array('foo'=>'bar'))
       }
    }

    ?>

    # mytemplate.php
    <? echo htmlspecialchars('<html>
        <body>')?>

            Hello, <? echo htmlspecialchars('<?= $foo ?>');?> // prints "bar"
    <? echo htmlspecialchars('    </body>
    </html>')?>        
        </pre>

    <h2>FAQs</h2>
    <strong>Why "peep"?</strong>
    <ul>
    <li>Because it is code that was written to be read</li>
    <li>Because it's small and doesn't make much noise</li>
    <li>Because it's an obtuse pronunciation of "php"</li>
    </ul>

    <h2>Todos</h2>
    <ul>
    <li>More model filter operators</li>
    <li>Customizable PK names</li>
    <li>Request class</li>
    <li>Query class?</li>
    <li>More controller logic</li>
    <li>More comments in the code</li>
    <li>Template inheritance</li>
    <li>Static url generator</li>
    <li>Method urls</li>
  </ul>
</body>
</html>