<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<?php
//database connectie, duhh!
$user = 'root';
$pass = '';
$db_conn = new PDO('mysql:host=localhost;dbname=superhero', $user, $pass);


// sql select statement die alle kolommen van de tabel superheroes ophaalt, met een limit van 200 rows
$sql = "SELECT * FROM superheroes LIMIT 200";
$statement = $db_conn->prepare($sql);
$statement->execute();
$database_gegevens = $statement->fetchAll(PDO::FETCH_ASSOC); //fetch alle data 

//onderstaande conditie checkt of het formulier verstuurd is
//onderstaande conditie checkt ook of het aantal karakters in het zoekveld groter of gelijk is aan 3 karakters
if (isset($_GET['submit']) && strlen($_GET['query']) >= 3) {
    $query = $_GET['query'];
    
    // onderstaande code zorgt ervoor dat er gekeken woord naar een zoekwoord die voor en na het woord nog karakters heeft ( met behulp van % )
    // Bijvoorbeeld: zoekwoord; 'weer' dan worden woorden gevonden die 'brandweer, weerbericht, weerman, weerribben, weertje' ook gevonden
    $query = '%'.$query . '%'; 

    //dit stukje code bepaalt of de category veilig en op de juiste wijze wordt ingevuld
    $toegestaande_category = array("title", "hair", "gender");//alleen deze categorieen mogen opgegeven worden (de gebruiker kan dus geen sql-injecties uitvoeren op de kolom)
    $key     = array_search($_GET['category'], $toegestaande_category);
    $kolom   = $toegestaande_category[$key];// de variabele kolom krijgt de waarde: "title", "hair", "gender"


    //sql statement die kijkt of het zoekwoord voorkomt in de kolom "title", "hair", "gender"
    $sql = "SELECT * FROM superheroes WHERE $kolom LIKE :ph_query LIMIT 200";
    $statement = $db_conn->prepare($sql);
    $statement->bindParam(":ph_query", $query);//verbind het zoekwoord aan de placeholder van de query
    $statement->execute();
    $database_gegevens = $statement->fetchAll(PDO::FETCH_ASSOC);//fetch alle data die overkomt met de query (kolomnaam en zoekwoord)
}

?>
<div class="container mt-2">
    <div class="row">
        <form action="" method="get">
            <div class="form-group">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="title" name="category" class="custom-control-input" value="title">
                    <label class="custom-control-label" for="title">Title</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="hair" name="category" class="custom-control-input" value="hair">
                    <label class="custom-control-label" for="hair">Hair</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="gender" name="category" class="custom-control-input" value="gender">
                    <label class="custom-control-label" for="gender">Gender</label>
                </div>
            </div>
            <div class="form-group">
                <label for="zoekwoord">Zoeken</label>
                <input type="text" name="query" id="zoekwoord">
                <input type="submit" value="Zoek!" name="submit" class="btn btn-success">
            </div>
        </form>
    </div>
    <table class="table table-hover ">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th>id</th>
                <th>Title</th>
                <th>gender</th>
                <th>weight</th>
                <th>Identity</th>
                <th>Eyes</th>
                <th>Hair</th>
                <th>PlaceOfBirth</th>
                <th>PlaceOfDeath</th>
                <th>Occupation</th>
                <th>Citizenship</th>
                <th>MaritalStatus</th>
            </tr>

        </thead>
        <tbody>
            <?php foreach ($database_gegevens as $key => $value) : ?>
                <tr>
                    <td><a href="delete.php"?id=<?php echo $value['ID'] ?>>delete</td>
                    <td><?php echo $value['ID'] ?></td>
                    <td><?php echo $value['Title'] ?></td>
                    <td><?php echo $value['Gender'] ?></td>
                    <td><?php echo $value['Weight'] ?></td>
                    <td><?php echo $value['Identity'] ?></td>
                    <td><?php echo $value['Eyes'] ?></td>
                    <td><?php echo $value['Hair'] ?></td>
                    <td><?php echo $value['PlaceOfBirth'] ?></td>
                    <td><?php echo $value['PlaceOfDeath'] ?></td>
                    <td><?php echo $value['Occupation'] ?></td>
                    <td><?php echo $value['Citizenship'] ?></td>
                    <td><?php echo $value['MaritalStatus'] ?></td>
                </tr>

            <?php endforeach; ?>
        </tbody>
</div>