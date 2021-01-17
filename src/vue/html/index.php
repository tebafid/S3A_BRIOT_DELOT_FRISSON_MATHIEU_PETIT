<!DOCTYPE HTML>
<html lang="fr">
<head>
    <title> MyWishList </title>
    <meta charset='utf-8'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<?php

include ("header.php"); // header (bare de navigation)

include ("body.php"); // contenu de la page

?>


</body>


</html>
<style>

    input[type=text], input[type=password], input[type=date] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }



    .button {
        width: 100%;
        padding: 12px;
        text-decoration: none;
        font-size: 18px;
        line-height: 25px;
        border-radius: 4px;
        background-color: #3e4a60;
        color: white;
        margin: 8px 0;
        margin-top: 20px;
    }

    .rouge{
        background-color: #b53434;
    }

    .reservButton {
        padding: 12px;
        background-color: #ffcf0d;
        text-decoration: none;
        font-size: 18px;
    }

    .content {
        margin-left: auto;
        margin-right: auto;
        padding: 20px;
        padding-top: 100px;
        padding-bottom: 50px;
        width: 75%;
    }

    .error {
        color: #ff1e1e;
        padding-bottom: 10px;
    }

    .styled-table {
        border-collapse: collapse;
        margin: 25px auto;
        font-size: 0.9em;
        font-family: sans-serif;
        min-width: 400px;
    }

    .styled-table thead tr {
        background-color: #3E4A60;
        color: #ffffff;
        text-align: left;
    }

    .styled-table th {
        padding: 12px 15px;
    }

    .styled-table td {
        padding: 0;
    }

    .styled-table tbody tr {
        border-bottom: 1px solid #dddddd;
        background-color: #ffffff;
    }

    .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }

    .styled-table tbody tr:hover {
        background-color: #e5e5e5;
    }

    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #3e4a60;
    }

    .styled-table tbody tr td div {
        border: 10px solid transparent;
    }

    .styled-table tbody tr td a{
        text-decoration: none;
        color: #3E4A60;
    }

    .styled-table tbody tr.active-row {
        font-weight: bold;
        color: #1e90ff;
    }



    .menu {
        width: 75%;
    }

    .menu a {
        background-color: #cfcfcf;
        color: black;
        display: inline-block;
        padding: 12px 25px;
        text-decoration: none;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .menu a:hover {
        background-color: #3e4a60;
        color: #e9cb83;
    }

    .menu a.active {
        background-color: #3e4a60;
        color: #e9cb83;
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background-color: #ececec;
    }

    .header {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;

        overflow: hidden;
        background-color: #272F3D;
    }

    .header a {
        float: left;
        color: lightgray;
        text-align: center;
        padding: 20px;
        text-decoration: none;
        font-size: 18px;
        line-height: 25px;
    }

    .header a.logo {
        font-size: 25px;
        font-weight: bold;
    }

    .header a:hover {
        color: white;
    }

    .header a.active {
        background-color: #ffcf0d;
        color: #272F3D;
    }

    .header-right {
        float: right;
    }

</style>