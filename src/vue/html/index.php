<!DOCTYPE HTML>
<html lang="fr">
<head>
    <title> MyWishlist </title>
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

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background-color: #ececec;
        color : #29313e;
    }

    .content {
        margin-left: auto;
        margin-right: auto;
        padding: 20px;
        padding-top: 100px;
        padding-bottom: 50px;
        width: 85%;
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
        font-size: 20px;
        line-height: 20px;
    }

    .header a.logo {
        font-size: 30px;
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

    .form{
        text-align: center;
    }

    .form div{
        text-align: left;
        padding-left: 2.5%;
    }

    input[type=text], input[type=password], input[type=date] {
        width: 95%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .button {
        width: 95%;
        padding: 10px;
        text-decoration: none;
        font-size: 20px;
        line-height: 20px;
        border-radius: 3px;
        background-color: #3e4a60;
        color: white;
        margin: 8px 0;
        margin-top: 20px;
    }

    .rouge{
        background-color: #b53434;
    }

    .error {
        color: #ff1e1e;
        padding-bottom: 10px;
    }

    .reservButton {
        padding: 12px;
        background-color: #ffcf0d;
        text-decoration: none;
        font-size: 20px;
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

    .styled-table {
        border-collapse: collapse;
        margin: 25px auto;
        font-size: 0.95em;
        font-family: sans-serif;
        min-width: 600px;
    }

    .styled-table th {
        padding: 8px 10px;
        color: white;
        background-color: #3E4A60;
        text-align: left;
    }

    .styled-table td {
        padding: 0;
    }

    .styled-table tbody tr {
        border-bottom: 1px solid #cfcfd2;
        background-color: white;
    }

    .styled-table tbody tr:nth-of-type(even) { /* 1 sur 2 */
        background-color: #f5f5f6;
    }

    .styled-table tbody tr:hover {
        background-color: #e5e5e5;
    }

    .styled-table tbody tr:last-of-type {
        border-bottom: 4px solid #3e4a60;
    }

    .styled-table tbody tr td div {
        border: 10px solid transparent;
    }

    .styled-table tbody tr td a{
        text-decoration: none;
        color: #1b2129;
    }
</style>