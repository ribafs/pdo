<html lang="en">

<head>

    <link rel="stylesheet prefetch" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.twbsPagination.min.js"></script>
    <style type="text/css" class="cp-pen-styles">
        .wrapper {
            margin: 60px auto;
            text-align: center;
        }
        h1 {
            margin-bottom: 1.25em;
        }
        #pagination-demo {
            display: inline-block;
            margin-bottom: 1.75em;
        }
        #pagination-demo li {
            display: inline-block;
        }
        .page-content {
            background: #eee;
            display: inline-block;
            padding: 10px;
            width: 100%;
            max-width: 660px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">

            <div class="row">
                <div class="col-sm-12">
                    <h1> Jquery Pagination Plugin demo </h1>
                    <ul id="pagination-demo" class="pagination-sm pagination"></ul>
                </div>
            </div>

            <div id="page-content" class="page-content">Page 16</div>
        </div>
    </div>
    <script>
        $('#pagination-demo').twbsPagination({
            totalPages: 6,
            visiblePages: 4,
            next: 'Next',
            prev: 'Prev',
            onPageClick: function(event, page) {
                //fetch content and render here
                $('#page-content').text('Page ' + page) + ' content here';

            }
        });
    </script>
</body>

</html>