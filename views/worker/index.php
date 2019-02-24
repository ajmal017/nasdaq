<?php

use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */

$this->title = 'Worker';

?>

<div class="worker">
    <form class="form">
        Company Symbol<br>
        <input type="text" class="symbol"><br>
        Start Date
        <?= DatePicker::widget([
            'name' => 'Start Date',
            'value' => '2019-02-24',
            'template' => '{addon}{input}',
            'options' => ['class' => 'start'],
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
        ]);?>
        End Date
        <?= DatePicker::widget([
            'name' => 'End Date',
            'value' => '2019-02-24',
            'template' => '{addon}{input}',
            'options' => ['class' => 'end'],
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
        ]);?>
        Email<br>
        <input type="email" class="email" required><br>
        <input type="submit" class="submit">
    </form>
    <div class="error"></div>

    <div id="linechart"></div>

    <div class="table"></div>

</div>
