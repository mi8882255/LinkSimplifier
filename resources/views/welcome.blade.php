<!DOCTYPE html>
<html>
    <head>
        <title>ApiDoc</title>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 800;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <h1>Api Usage:</h1>
                <p>You can use JSONP get requests for add/moduify data.</p>
                <p>Routes list:</p>
<table border="1">
    <thead>
    <tr>
        <th>Url</th><th>Description</th><th>Request params</th><th>Response structure</th><th></th>
    </tr>
    </thead>
    <tbody>
    <?php
            $tableArr=[
                    ['/api/bookmark/add','add new bookmark','url','id',''],
                    ['/api/bookmark/get10','get last 10 bookmarks','-',"0:['id','url'],1:...",''],
                    ['/api/bookmark/getbyid','get bookmark by id with comments','id',"'id','url','comments':[0:['id','created','updated','text'],1:...]",''],
                    ['/api/comments/add','add new comment','bm_id,text','id',''],
                    ['/api/comments/modify','modify comment','id,text','id',''],
                    ['/api/comments/delete','delete comment','id','true|false','']
            ];

    foreach ($tableArr as $line) {
        echo "<tr>";
        foreach ($line as $el) {
            echo "<td>".$el."</td>";
        }
        echo "</tr>";
    }
    ?>

    </tbody>
</table>
                <hr>
                <p>JQuery usage example:</p>
                <code>
                    $.ajax({
                    url: "/api/bookmark/add",
                    jsonp: "callback",
                    dataType: "jsonp",
                    data: {
                    url: "http://ya.ru"
                    },

                    success: function( response ) {
                    console.log( response );
                    }
                    });
                </code><hr>
                <p>Successful request/response example</p>
                <code>http://ls.none.in.ua/api/bookmark/add?callback=jQuery190009163928138808375_1468413626064&url=http%3A%2F%2Fya.ru&_=1468413626065</code><br>
                <code>
                    jQuery190009163928138808375_1468413626064({state: true, resp: 2});</code><hr>
                <p>Failed request/response example</p>
                <code>
                    http://ls.none.in.ua/api/bookmark/add?callback=jQuery190009163928138808375_1468413626064&url=gyya.ru&_=1468413626066</code><br>
                <code>
                    jQuery190009163928138808375_1468413626064({state: false, err: {url: ["validation.u_r_l"]}});
                </code>
            </div>
        </div>
    </body>
</html>
