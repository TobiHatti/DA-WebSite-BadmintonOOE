<?php
    require("header.php");
    PageTitle("Import-Test");

     if(isset($_POST['start_import']))
     {
       /*$row = 1;
       if (($handle = fopen("test.csv", "r")) !== FALSE) {
           while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
               $num = count($data);
               echo "<p> $num fields in line $row: <br /></p>\n";
               $row++;
               for ($c=0; $c < $num; $c++) {
                    echo $data[$c] . "<br />\n";
                }
            }
            fclose($handle);
        }*/

        class Csv2Array{
          public $file="C:\xampp\htdocs\Badminton\test.csv";
          public $header;
          private $lines;
          private $position;

          function __construct($file)
          {
            $this->file = $file;
            $this->readLines();
            $this->readHeader();
            $this->position = 1;
          }



          private function readHeader()
          {
               $this->header = split(';',$this->lines[0]);
          }

          public function getNextRow()
          {
            if($this->lines[$this->position])
            {
             $line = split(';',$this->lines[$this->position]);
             $columnposition = 0;
             foreach($this->header as $column)
             {
             $res[$column] = $line[$columnposition];
             $columnposition++;
             }
                $this->position++;
            }
                return $res;
          }

          private function readLines()
          {
            if($handle = fopen($this->file, "r"))
            {
                $this->lines=file($this->file);
                return true;
            }
            else
            {
            return false;
            }
          }
        }
    }


    echo'
        <h2 class="stagfade1">CSV-Datei importieren</h2>
        <hr>
        <form action="'.ThisPage().'" method="post" accept-charset="utf-8" enctype="multipart/form-data" class="stagfade2">

        <button type="submit" name="start_import" class="stagfade3">Importiere CSV</button>
        </form>
        <table>
          <thead>
            <tr>
        ';






    if(isset($_POST['start_import']))
    {
      <?php>

      <?>
        foreach($csv2array->header as $column){
          echo "<td>".$column."</td>";
        }


            </tr>
          </thead>
          <tbody>


              while($row = $csv2array->getNextRow()){
                echo "<tr>";
                  foreach($row as $column){
                    echo "<td>".$column."</td>";
                  }
                echo "</tr>";
              }

          </tbody>
        </table>
    }

    include("footer.php");





?>
