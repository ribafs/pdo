# Paginator

## About
Pagiantor is a basic class for PHP which enables easy pagination of data retrieved from a database, using PDO prepared statements.

### Features
* Database queries through PDO prepared statements to protect against SQL injection attacks
* Compatability with MySQL, MariaDB, H2, HSQLDB, Postgres, and SQLite ([due to use of LIMIT ... OFFSET clause](http://www.jooq.org/doc/latest/manual/sql-building/sql-statements/select-statement/limit-clause/))
* Navigation customisable though user-specified class names
* Ability to change number of results per page
* Object-oriented approach

## Usage

Note, it is advisable to have a working knowledge of PDOs and PDOStatements before using this class. For more information on these objects please consult the [PHP manual](http://php.net/manual/en/book.pdo.php). To explain the usage we will run through an example, wherein we wish to paginate data from a table called `articles` which has columns `id`, `title` and `author`. Throughout this example we will assume that `$pdo` is a valid PDO object connected to the database containing this table.

### Setup a new Paginator
Before we can use the class we need to include the class file. Download the class file, place it somewhere in the file structure of your project and include it at the top of the PHP file upon which you wish to display the paginated data. 

```php
include_once "path_to/Pagiantion.class.php";
```
Next we will construct a new Paginator object. The constructor function requires 2 inputs:
1. A PDO object which should be connected to your database,
2. The query returning the data you wish to paginate

Optionally, the user may also provide a callback function. This function should accept a PDO object and should bind any parameters in your query.

```php
$query = "SELECT * FROM articles WHERE author LIKE :author";

// Callback function which will bind $_GET['author'] to the :author parameter
function bind_my_params($stmt) {
  $stmt->bindValue(':author',$_GET['author']);
}

// Finally we set up our Paginator
$paginator = new Paginator($pdo,$query,'bind_my_prams');
```

Please note the paginator can automatically detect how many results your query will return. To do this it creates a table with the alias `pagiantor_count_table` so you should avoid using this as an alias in your queries.

### Update the page
To update the page we must tell the paginator which page we are on and how many results we are displaying per page. These values will be `$_GET['page']` and `$_GET['limit']` respectively. In case we have arrived on the page without these values specified in the query we must set some defaults.

```php
  // We default to page 1, displaying 5 results
  $_GET['page'] = (empty($_GET['page'])) ? 1 : $_GET['page'];
  $_GET['limit'] = (empty($_GET['limit'])) ? 5 : $_GET['limit'];
  // Now we update these values in the Paginator
  $paginator->updatePage($_GET['page'],$_GET['limit']);
```

Note, if you do not provide these arguments to `updatePage()`, the Paginator will default the first argument to page `1` and the second argument to `total_rows` and will display all results on page 1. The Paginator will then execute your callback function to bind the relavent paramaters, it will bind its own paramaters and then execute the query. The method will return a boolean of `TRUE` on success and `FALSE` on failure.

### Retreive rows
The Paginator now has all the data from its query stored in a PDOStatement object. To retrieve this data we can call `$paginator->fetchNextRow($fetch_style)`. Iternally this just calls `fetch($fetch_style)` on the PDOStatement, so you can use any of the [usual fetch styles](http://php.net/manual/en/pdostatement.fetch.php). By default, we use `PDO::FETCH_ASSOC`. It is recommended you now use a `while` loop to process each row in turn.

```html
<table>
  <tr>
    <th>ID</th>
    <th>Title</th>
    <th>Author</th>
  </tr>
  <?php while ($row = $paginator->fetchNextRow()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= $row['title'] ?></td>
      <td><?= $row['author'] ?></td>
    </tr>
  <?php endwhile; ?>
</table>
```

### Setup navigation
We would now like to have a set of links at the bottom of the page which allow us to navigate through the paginated content. Paginator seperates the numerical links and the relative links allowing for greater design flexibility. Let's a create a navigation bar which includes 5 numerical links, including the current page as well as a first, last, previous and next page button.

```html
<ul class = "pagination_list">
  <li class="pagination_nav_arrow"><a href="?<?= $paginator->getFirstPageQuery()?>">&lt;&lt;</a></li>
  <li class="pagination_nav_arrow"><a href="?<?= $paginator->getPrevPageQuery()?>">&lt;</li>
  <li class="pagination_ellipses">...</li>
  <?= $paginator->getPagination(5, 'pagination_link', 'current_page'); ?>
  <li class="pagination_ellipses">...</li>
  <li class="pagination_nav_arrow"><a href="?<?= $paginator->getNextPageQuery()?>">&gt;</li>
  <li class="pagination_nav_arrow"><a href="?<?= $paginator->getLastPageQuery()?>">&gt;&gt;</a></li>
</ul>
```

The `getPagination()` method will return 5 items of the form
```html
<li class='pagination_link'><a href='?page=i&limit=current_limit'> i </a></li>
```
where `i` is the page number being linked to and `current_limit` is the current number of posts per page. Moreover, the `li` item which correspondes to the current page will have `class='pagination_link current_page'`. This allows for great flexibility when designing your navigation bar.

To get the relative navagation buttons we have used the following methods:

* `getFirstPageQuery()`
* `getLastPageQuery()`
* `getPrevPageQuery()`
* `getNextPageQuery()`

Each of these will return a PHP query string (without the `?`). This string will copy all of the key, value pairs already present in `$_GET` but replace the value of `page` with the relavent integer. Please note, you must call `updatePage()` with the appropriate parameters prior to calling these methods.

Here is an example of what sort of pagination can be achieved with this approach:

<!-- Include GIF of example navigation here -->

### Setup 'results per page' buttons
When displaying paginated results, it is often desirable to allow the user to change number of results on each page. The Paginator enables this through the `getNewLimitQuery($newLimit)` method. The method accepts one argument which is the amount of results you wish to show and returns a PHP query (without the `?`). This query will change the `limit` and `page` parameters so that the result previously displayed at the top, is visible on the new page. All other parameters in the present query will remaind unchanged. You can then link to these queries with an anchor tag however you desire.

```html
<ul>
    <li>Results per page:</li>
    <li><a href="?<?= $paginator->getNewLimitQuery(5);?>">5</a></li>
    <li><a href="?<?= $paginator->getNewLimitQuery(10);?>">10</a></li>
    <li><a href="?<?= $paginator->getNewLimitQuery(15);?>">15</a></li>
</ul>
```

## To-do
* ~~Automatically detect total number of rows from base query~~
* Add support for more databases
* Add exceptions
* Better string handling
* Add custom pagination format
* Do better checks in all methods

## References
* https://code.tutsplus.com/tutorials/how-to-paginate-data-with-php--net-2928
