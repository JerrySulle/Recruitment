<?php

class Input {
    private int $total_value = 0;
    private array $pecahan_input ;

    public function __construct(int $total_value , array $pecahan_input)
    {
        $this->total_value = $total_value;
        $this->pecahan_input = $pecahan_input;
    }

    public function getTotalValue(): int
    {
        return $this->total_value;
    }

    public function getPecahanInput(): array
    {
        return $this->pecahan_input;
    }

    public function setTotalValue(int $total_value): void
    {
        $this->total_value = $this->total_value+$total_value;
    }

    public function changeTotalValueAndPecahanInput(int $total_value , array $pecahan_input): void
    {
        $this->total_value = $total_value;
        $this->pecahan_input = $pecahan_input ;
    }

    public function setPecahanInput(array $pecahan_input): void
    {
        $this->pecahan_input = array_merge($this->pecahan_input,$pecahan_input);
    }
}

class PecahanInput {
    private int $value;

    public function __construct(int $value = 0)
    {
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): void
    {
        $this->value = $value;
    }
}

//INPUTS =======================================================================================================================================================
$input1 = [
    "price" => 10000,
    "pecahan" => [
        [
            "value" => 5000
        ],
        [
            "value" => 5000
        ]
    ]
];

$input2 = [
    "price" => 40000,
    "pecahan" => [
        [
            "value" => 20000
        ],
        [
            "value" => 20000
        ]
    ]
];

$input3 = [
    "price" => 35000,
    "pecahan" => [
        [
            "value" => 20000
        ],
        [
            "value" => 20000
        ]
    ]
];
//=====================================================================================================================================================================

//Function untuk mencari pecahan uang yang ingin diambil
function findMoney($pecah,$input,$sisa){
    $pecahan_input = [];
    $i = 0;
    foreach ($pecah as $item) {
        if($item == $sisa){
            array_splice($pecah, $i, $i);
            $input->setTotalValue((-1) * $sisa);
        }
        $i++;
    }
}

//Function untuk mencari pecahan untuk kembalian
function cariPecahan($input,$key){
    $pecahan_input = [];
    $pecahan = $input->getPecahanInput();
    $total_value = $input->getTotalValue();
    foreach ($pecahan as $item) {
        $pecahan_input[] = $item;
    }
    for($i = 0; $i < count($pecahan_input); $i++){
        if($pecahan_input[$i]->getValue() == $key) {
            return true;
        }
    }
    return false;
}

//Function untuk meng-input data baru (Memasukkan uang dan nominalnya)
function inputted($value,$pecah,$input){
    $total = 0;
    $pecahan_input = [];
    foreach ($pecah as $item) {
        $pecahan_input[] = new PecahanInput(
            $item["value"]
        );
        $total = $total + $item["value"];
    }

    if($value > $total){
        print_r("Error". "\n");
        return 0;
    }else if($value < $total){
        $kembalian = $total - $value;
        if(cariPecahan($input,$kembalian) == true){
            print_r("Kembalian: " . $kembalian . "\n");
        }else{
            print_r("Error Tidak ada Kembalian". "\n");
            return 0;
        }
    }

    $input->setTotalValue($value);
    $pecahan_input = [];
    foreach ($pecah as $item) {
        $pecahan_input[] = new PecahanInput(
            $item["value"]
        );
    }
    $input->setPecahanInput($pecahan_input);
    //return 1;
}

//Function untuk mengambil uang
function Take ($input,$value){
    $pecahan_input = $input->getPecahanInput();
    $total_value = $input->getTotalValue();
    $i = 0;
    $temp_val = $value;
    $pecah = [];

    foreach ($pecahan_input as $item) {
        $pecah[] = $item->getValue();
    }

    while($temp_val > 0) {
        if ($temp_val >= $pecah[$i]) {
            $temp_val = $temp_val - $pecah[$i];
            $total_value = $total_value - $pecah[$i];
            if($total_value < 0){
                print_r("Error total uang tidak boleh kurang dari nol". "\n");
                return 0;
            }
            print_r("Pecahan yang diambil : " . $pecah[$i] . "\n");
            $pecah[$i] = 0;
        } else {
            $pecah[$i] = $pecah[$i] - $temp_val;
            $total_value = $total_value + $temp_val;
            $temp_val = 0;
            print_r("Error tidak ada pecahan yang dapat memenuhi uang yang diambil". "\n");
            return 0;
        }
        $i++;
    }

    echo($pecah[0]);

    for($i=0;$i<count($pecah);$i++){
        $pecah1[] = new PecahanInput($pecah[$i]);
    }

    $input->changeTotalValueAndPecahanInput($total_value,$pecah1);
}

//Function untuk print total uang dan pecahannya
function printall ($input){
    $pecahan_input = $input->getPecahanInput();
    $total_value = $input->getTotalValue();
    $i = 0;
    $pecah = [];

    echo("Total Value : ".$total_value."\n");

    try {
        foreach ($pecahan_input as $item) {
            $pecah[] = $item->getValue();
        }
    } catch (Exception $e) {
        print_r("Error");
    }

//    foreach ($pecahan_input as $item) {
//        $pecah[] = $item->getValue();
//    }

    foreach ($pecah as $item) {
        echo("Pecahan ". $i ." : ".$item."\n");
        $i++;
    }
}

$input = new Input(0,[]);

inputted($input1['price'],$input1['pecahan'],$input);
print_r("\nSETELAH INPUT1 DIMASUKKAN\n");
printall($input);
inputted($input2['price'],$input2['pecahan'],$input);
print_r("\nSETELAH INPUT2 DIMASUKKAN\n");
printall($input);
inputted($input3['price'],$input3['pecahan'],$input);
print_r("\nSETELAH SEMUA INPUT DIJALANKAN\n");

//print_r($input->getTotalValue());
//print_r($input->getPecahanInput());

$uang_ambil = 5000;


printall($input);

Take($input,$uang_ambil);

print_r("\nSETELAH DIAMBIL LIMA RIBU\n");
printall($input);

//print total value new line
//print_r("Total: " . $input->getTotalValue() . "\n");
//print_r($input->getPecahanInput());

//echo($input->getTotalValue());
//print_r($input->getPecahanInput());



