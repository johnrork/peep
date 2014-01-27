<html>
    <head>
        <style>
            table{
                border-collapse: collapse;
                margin: auto;
            }
            th, td{
                border: 1px solid #ccc;
                padding: 10px;
                text-align: left;
            }
        </style>
    </head>
    <body>
        <table>
            <tr>
                <th>Username</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Email</th>
            </tr>
            <? foreach($users as $user): ?>
                <tr>
                    <td><?= $user->username ?></td>
                    <td><?= $user->first_name ?></td>
                    <td><?= $user->last_name ?></td>
                    <td><?= $user->email ?></td>
                </tr>
            <? endforeach ?>
        </table>
    </body>
</html>

