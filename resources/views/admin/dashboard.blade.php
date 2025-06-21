<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - EVUP</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap");

        /*-----------ROOT VARIABLES-------------*/
        :root{
            --color-primary : #7380ec;
            --color-danger : #ff7782;
            --color-success : #41f1b6;
            --color-warning : #ffbb55;
            --color-white : #ffffff;
            --color-info-dark : #7d8da1;
            --color-info-light : #dce1eb;
            --color-dark : #363949;
            --color-light : rgba(132, 139, 200, 0.18);
            --color-primary-variant : #111e88;
            --color-dark-variant : #677483;
            --color-background : #f6f6f9;

            --card-border-radius : 2rem;
            --border-radius-1 : 0.4rem;
            --border-radius-2 : 0.8rem;
            --border-radius-3 : 1.2rem;

            --card-padding : 1.8rem;
            --padding-1 : 1.2rem;

            --box-shadow : 0 2rem 3rem var(--color-light);
        }

        /* DARK THEME VARIABLES */
        .dark-theme-variables {
            --color-background: #181a1e;
            --color-white: #202528;
            --color-dark: #edeffd;
            --color-dark-variant: #a3bdcc;
            --color-light: rgba(0, 0, 0, 0.4);
            --box-shadow: 0 2rem 3rem var(--color-light);
        }

        *{
            margin : 0;
            padding : 0;
            outline : 0;
            appearance : none;
            border : 0;
            text-decoration : none;
            list-style : none;
            box-sizing : border-box;
        }

        html{
            font-size : 14px;
        }

        body{
            width: 100vw;
            height : 100vh;
            font-family : poppins, sans-serif;
            font-size : 0.88rem;
            background : var(--color-background);
            user-select : none;
            overflow-x : hidden;
            color: var(--color-dark);
        }

        .container{
            display : grid;
            width : 96%;
            margin : 0 auto;
            gap : 1.8rem;
            grid-template-columns : 14rem auto 23rem; /* Sidebar, Main, Right Section */
        }

        a{
            color : var(--color-dark);
        }

        img{
            display : block;
            width : 100%;
        }

        h1 {
            font-weight : 800;
            font-size : 1.4rem;
        }

        h2{
            font-size : 1.4rem;
        }

        h3{
            font-size : 0.87rem;
        }

        h4{
            font-size : 0.8rem;
        }

        h5{
            font-size : 0.77rem;
        }

        small{
            font-size : 0.75rem;
        }

        .profile-photo{
            width : 2.8rem;
            height : 2.8rem;
            border-radius : 50%;
            overflow : hidden;
        }

        .text-muted{
            color : var(--color-info-dark);
        }

        p{
            color : var(--color-dark-variant);
        }

        b{
            color : var(--color-dark);
        }

        .primary{
            color : var(--color-primary);
        }

        .danger{
            color : var(--color-danger);
        }

        .success{
            color : var(--color-success);
        }

        .warning{
            color : var(--color-warning);
        }

        /* ======================== ASIDE (SIDEBAR) ===========================*/
        aside{
            height : 100vh;
            background :var(--color-background);
        }

        aside .top{
            background : var(--color-white);
            display : flex;
            align-items : center;
            justify-content : space-between;
            margin-top : 1.4rem;
            padding-bottom: 1.4rem;
        }

        aside .logo{
            display : flex;
            gap : 0.8rem;
            padding-left: 1.8rem;
            align-items: center; /* Center logo with text */
        }

        aside .logo img{
            width : 2rem;
            height : 2rem;
        }

        aside .close{
            display : none;
        }

        /*=============== SIDEBAR ===============*/
        aside .sidebar{
            display : flex;
            flex-direction : column;
            height : 86vh;
            position : relative;
            top : 3rem;
        }

        aside h3{
            font-weight : 500;
        }

        aside .sidebar a{
            display : flex;
            color : var(--color-info-dark);
            margin-left : 2rem;
            gap : 1rem;
            align-items : center;
            position : relative;
            height : 3.7rem;
            transition : all 300ms ease;
        }

        aside .sidebar a span{
            font-size : 1.6rem;
            transition : all 300ms ease;
        }

        aside .sidebar a:last-child{
            position : absolute;
            bottom : 2rem;
            width : 100%;
        }

        aside .sidebar a.active{
            background : var(--color-light);
            color : var(--color-primary);
            margin-left : 0;
            border-radius: var(--border-radius-1); /* Slight rounding for active background */
        }

        aside .sidebar a.active:before{
            content : '';
            width : 6px;
            height : 100%;
            background : var(--color-primary);
            border-top-right-radius: var(--border-radius-1);
            border-bottom-right-radius: var(--border-radius-1);
        }

        aside .sidebar a.active span{
            color : var(--color-primary);
            margin-left : calc(1rem - 3px);
        }

        aside .sidebar a:hover{
            color : var(--color-primary);
        }

        aside .sidebar a:hover span{
            margin-left : 1rem;
        }

        aside .sidebar .message-count{
            background : var(--color-danger);
            color : var(--color-white);
            padding : 2px 10px;
            font-size : 11px;
            border-radius : var(--border-radius-1);
        }


        /* ======================== MAIN ===========================*/
        main{
            margin-top : 1.4rem;
        }

        /* Content sections within main */
        main .content-section {
            display: none; /* Hidden by default */
        }

        main .content-section.active {
            display: block; /* Shown when active */
        }


        main .date{
            display : inline-block;
            background : var(--color-light);
            border-radius : var(--border-radius-1);
            margin-top : 1rem;
            padding : 0.5rem 1.6rem;
        }

        main .date input[type="date"]{
            background : transparent;
            color : var(--color-dark);
            font-family: inherit; /* Inherit font from body */
        }

        main .insights{
            display : grid;
            grid-template-columns : repeat(3, 1fr);
            gap : 1.6rem;
            margin-top: 1rem; /* Adjust margin for insights section */
        }

        main .insights > div{
            background : var(--color-white);
            padding : var(--card-padding);
            border-radius : var(--card-border-radius);
            margin-top : 1rem;
            box-shadow : var(--box-shadow);
            transition : all 300ms ease;
        }

        main .insights > div:hover {
            box-shadow :none;
        }

        main .insights > div span{
            background : var(--color-primary);
            padding : 0.5rem;
            border-radius : 50%;
            color : var(--color-white);
            font-size : 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3.5rem;
            height: 3.5rem;
            /* For the image, it's inside the span */
        }

        /* Specific icon colors from the image */
        main .insights > div.sales span {
            background : var(--color-primary); /* Already defined */
        }

        main .insights > div.expenses span {
            background : var(--color-danger);
        }

        main .insights > div.income span {
            background : var(--color-success);
        }

        main .insights > div .middle {
            display : flex;
            align-items : center;
            justify-content : space-between;
            margin-top: 1rem; /* Space between icon and middle content */
        }

        main .insights h3{
            margin : 0; /* Remove default h3 margin */
            font-size : 1rem;
        }

        main .insights .progress{
            position : relative;
            width : 92px; /* Set fixed size for circle */
            height : 92px;
            border-radius : 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        main .insights svg{
            width : 100%;
            height : 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        main .insights svg circle {
            fill : none;
            stroke : var(--color-primary); /* Default stroke color */
            stroke-width : 14;
            stroke-linecap : round;
            transform : translate(5px, 5px); /* Centers the circle for the stroke width */
        }

        /* Specific circle colors and dash offsets from the image */
        main .insights .sales svg circle {
            stroke: var(--color-primary);
            stroke-dasharray : 200; /* Full circle (approx) */
            stroke-dashoffset : 160; /* 81% filled */
        }

        main .insights .expenses svg circle {
            stroke: var(--color-danger);
            stroke-dasharray : 200;
            stroke-dashoffset : 76; /* 62% filled (200 * 0.38) */
        }

        main .insights .income svg circle {
            stroke: var(--color-success);
            stroke-dasharray : 200;
            stroke-dashoffset : 112; /* 44% filled (200 * 0.56) */
        }


        main .insights .progress .number{
            position : absolute;
            top : 0;
            left : 0;
            height : 100%;
            width : 100%;
            display : flex;
            justify-content : center;
            align-items : center;
        }

        main .insights .progress .number p {
            color: var(--color-dark); /* Ensure number color changes with theme */
        }

        /* ======================== RECENT ORDERS (Événements Récents) ===========================*/
        main .recent-orders {
            margin-top: 3rem; /* Space after insights */
        }

        main .recent-orders h2 {
            margin-bottom: 0.8rem;
            color: var(--color-dark); /* Ensure title color changes with theme */
        }

        main .recent-orders table {
            background: var(--color-white);
            width: 100%;
            border-radius: var(--card-border-radius);
            padding: var(--card-padding);
            text-align: center;
            box-shadow: var(--box-shadow);
            transition: all 300ms ease;
            border-collapse: collapse; /* Remove space between cells */
        }

        main .recent-orders table:hover {
            box-shadow: none;
        }

        main table thead tr th {
            padding: 1.2rem 0;
            color: var(--color-dark-variant); /* Header text color */
            border-bottom: 1px solid var(--color-info-light); /* Separator */
        }

        main table tbody td {
            height: 2.8rem;
            border-bottom: 1px solid var(--color-info-light);
            color: var(--color-dark-variant);
        }

        main table tbody tr:last-child td {
            border-bottom: none; /* No border for the last row */
        }

        main .recent-orders a {
            text-align: center;
            display: block;
            margin: 1rem auto;
            color: var(--color-primary);
        }

        /* ======================== RIGHT SECTION (TOP NAV & USER PROFILE) ===========================*/
        .right {
            margin-top: 1.4rem;
        }

        .right .top {
            display: flex;
            justify-content: end;
            gap: 2rem;
            align-items: center; /* Vertically align items */
        }

        .right .top button {
            display: none;
        }

        .right .theme-toggler {
            background: var(--color-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 1.6rem;
            width: 4.2rem;
            cursor: pointer;
            border-radius: var(--border-radius-1);
        }

        .right .theme-toggler span {
            font-size: 1.2rem;
            width: 50%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .right .theme-toggler span.active {
            background: var(--color-primary);
            color: var(--color-white); /* White text on active theme button */
            border-radius: var(--border-radius-1);
        }

        .right .top .profile {
            display: flex;
            gap: 2rem;
            text-align: right;
            align-items: center; /* Vertically align profile photo and info */
        }

        .right .profile .info h4 {
            margin-bottom: 0.2rem; /* Reduce space below name */
            color: var(--color-dark); /* Ensure name color changes with theme */
        }

        .right .profile .info small {
            color: var(--color-info-dark); /* Role color */
        }

        /* ======================== RECENT UPDATES ===========================*/
        .right .recent-updates {
            margin-top: 2rem;
        }

        .right .recent-updates h2 {
            margin-bottom: 0.8rem;
            color: var(--color-dark);
        }

        .right .recent-updates .updates {
            background: var(--color-white);
            padding: var(--card-padding);
            border-radius: var(--card-border-radius);
            box-shadow: var(--box-shadow);
            transition: all 300ms ease;
        }

        .right .recent-updates .updates:hover {
            box-shadow: none;
        }

        .right .recent-updates .updates .update {
            display: grid;
            grid-template-columns: 2.6rem auto 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: center;
        }

        .right .recent-updates .updates .update:last-child {
            margin-bottom: 0; /* No margin for last update */
        }

        .right .recent-updates .updates .update .message {
            color: var(--color-dark-variant); /* Text color for messages */
        }

        .right .recent-updates .updates .update .message b {
            color: var(--color-dark); /* Bold names color */
        }

        /* ======================== ANALYTICS ===========================*/
        .right .sales-analytics {
            margin-top: 2rem;
        }

        .right .sales-analytics h2 {
            margin-bottom: 0.8rem;
            color: var(--color-dark);
        }

        .right .sales-analytics .item {
            background: var(--color-white);
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.7rem;
            padding: 1.4rem var(--card-padding);
            border-radius: var(--border-radius-3);
            box-shadow: var(--box-shadow);
            transition: all 300ms ease;
        }

        .right .sales-analytics .item:hover {
            box-shadow: none;
        }

        .right .sales-analytics .item .right {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin: 0;
            width: 100%;
        }

        .right .sales-analytics .item .icon {
            background: var(--color-primary);
            padding: 0.6rem;
            display: flex;
            border-radius: 50%;
            color: var(--color-white);
            font-size: 1.6rem; /* Adjust icon size */
            align-items: center;
            justify-content: center;
        }

        .right .sales-analytics .item.offline .icon {
            background: var(--color-danger);
        }

        .right .sales-analytics .item.customers .icon {
            background: var(--color-success);
        }

        .right .sales-analytics .item .info h3 {
            margin-bottom: 0.2rem;
            color: var(--color-dark);
        }

        .right .sales-analytics .item h3 {
            font-size: 1.1rem; /* Adjust value size */
            color: var(--color-dark);
        }

        .right .sales-analytics .add-product {
            background: transparent;
            border: 2px dashed var(--color-primary);
            color: var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.7rem;
            padding: 1.4rem var(--card-padding);
            border-radius: var(--border-radius-3);
            cursor: pointer;
            transition: all 300ms ease;
        }

        .right .sales-analytics .add-product:hover {
            background: var(--color-primary);
            color: var(--color-white);
        }

        .right .sales-analytics .add-product div {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .right .sales-analytics .add-product div h3 {
            font-weight: 600;
        }

        /* ======================== GESTION DES ORGANISATEURS (Table) Styles ===========================*/
        .gestion-organisateurs {
            margin-top: 2rem;
        }

        .gestion-organisateurs h1 {
            margin-bottom: 1rem;
        }

        .gestion-organisateurs .card { /* Simulating the card from your image */
            background: var(--color-white);
            border-radius: var(--card-border-radius);
            box-shadow: var(--box-shadow);
            padding: var(--card-padding);
            margin-top: 1.5rem;
        }

        .gestion-organisateurs .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--color-info-light);
            margin-bottom: 1.5rem;
        }

        .gestion-organisateurs .card-header h6 {
            margin: 0;
            font-weight: 600;
            color: var(--color-primary);
        }

        .gestion-organisateurs .list-link {
            color: var(--color-primary);
            font-size: 0.9rem;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .gestion-organisateurs .dataTables_wrapper {
            margin-top: 1rem;
        }

        .gestion-organisateurs .dataTables_length,
        .gestion-organisateurs .dataTables_filter {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .gestion-organisateurs .dataTables_length {
            justify-content: flex-start;
        }

        .gestion-organisateurs .dataTables_filter {
            justify-content: flex-end;
        }

        .gestion-organisateurs .dataTables_filter input {
            border: 1px solid var(--color-info-light);
            border-radius: var(--border-radius-1);
            padding: 0.5rem 0.8rem;
            background: var(--color-light); /* Lighter background for input */
            color: var(--color-dark);
        }

        .gestion-organisateurs table.dataTable {
            width: 100% !important; /* Ensure table uses full width */
            border-collapse: collapse;
        }

        .gestion-organisateurs table.dataTable th,
        .gestion-organisateurs table.dataTable td {
            padding: 0.8rem;
            border-bottom: 1px solid var(--color-info-light);
            text-align: center;
            color: var(--color-dark);
        }

        .gestion-organisateurs table.dataTable thead th {
            background: var(--color-light); /* Light background for table header */
            font-weight: 600;
        }

        .gestion-organisateurs table.dataTable tbody tr:last-child td {
            border-bottom: none;
        }

        /* Badges for status */
        .gestion-organisateurs .badge {
            padding: 0.3em 0.6em;
            border-radius: var(--border-radius-1);
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            display: inline-block;
            color: var(--color-white);
        }

        .gestion-organisateurs .badge-success { background-color: var(--color-success); }
        .gestion-organisateurs .badge-warning { background-color: var(--color-warning); }
        .gestion-organisateurs .badge-danger { background-color: var(--color-danger); } /* For Refusé */

        /* Action Buttons */
        .gestion-organisateurs .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            border-radius: 0.2rem;
            border: none;
            cursor: pointer;
            transition: all 300ms ease;
            color: var(--color-white);
        }
        .gestion-organisateurs .btn-sm:hover {
            opacity: 0.9;
        }

        .gestion-organisateurs .btn-success { background-color: var(--color-success); }
        .gestion-organisateurs .btn-danger { background-color: var(--color-danger); }
        .gestion-organisateurs .btn-info { background-color: var(--color-primary); /* Using primary for info like in your theme */ }
        .gestion-organisateurs .btn-warning { background-color: var(--color-warning); }

        /* Toggle switch */
        .gestion-organisateurs .toggle-icon {
            font-size: 1.8rem; /* Larger icon for toggle */
            cursor: pointer;
            vertical-align: middle;
            display: inline-block; /* To allow margin/padding adjustments */
            margin: 0 0.2rem;
        }

        .gestion-organisateurs .toggle-on { color: var(--color-success); }
        .gestion-organisateurs .toggle-off { color: var(--color-danger); }

        /* Pagination */
        .gestion-organisateurs .dataTables_paginate {
            display: flex;
            justify-content: center;
            margin-top: 1.5rem;
        }

        .gestion-organisateurs .paginate_button {
            background: var(--color-light);
            border: 1px solid var(--color-info-light);
            padding: 0.5rem 1rem;
            margin: 0 0.2rem;
            border-radius: var(--border-radius-1);
            cursor: pointer;
            color: var(--color-dark);
            transition: all 300ms ease;
        }

        .gestion-organisateurs .paginate_button.current,
        .gestion-organisateurs .paginate_button:hover {
            background: var(--color-primary);
            color: var(--color-white);
            border-color: var(--color-primary);
        }
        .gestion-organisateurs .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Adjustments for the small bar at the bottom of the table from the image */
        .gestion-organisateurs .dataTables_scrollBody::after {
            content: '';
            display: block;
            height: 5px; /* Adjust height as needed */
            background: var(--color-light); /* Or a specific color for the bar */
            border-bottom-left-radius: var(--border-radius-1);
            border-bottom-right-radius: var(--border-radius-1);
            margin-top: -1px; /* Overlap slightly with table for smooth look */
        }
        .gestion-organisateurs .dataTables_info {
            color: var(--color-info-dark);
            font-size: 0.85rem;
            margin-top: 1rem;
            text-align: center;
        }


        /* ======================== MEDIA QUERIES (RESPONSIVENESS) ===========================*/

        /* Tablets */
        @media screen and (max-width: 1200px) {
            .container {
                width: 94%;
                grid-template-columns: 7rem auto 23rem;
            }

            aside .sidebar h3 {
                display: none;
            }

            aside .sidebar a {
                width: 5.6rem;
            }

            aside .sidebar a:last-child {
                position: relative;
                margin-top: 1.8rem;
            }

            main .insights {
                grid-template-columns: 1fr;
                gap: 0;
            }

            /* Adjustments for tables on smaller screens */
            main .recent-orders table,
            .gestion-organisateurs table.dataTable {
                width: 100%; /* Ensure tables fit */
                table-layout: fixed; /* Helps prevent overflow */
            }
            main .recent-orders table th,
            main .recent-orders table td,
            .gestion-organisateurs table.dataTable th,
            .gestion-organisateurs table.dataTable td {
                white-space: nowrap; /* Keep content on single line */
                overflow: hidden;
                text-overflow: ellipsis; /* Add ellipsis for overflow */
            }


            .right {
                width: 94%;
                margin: 0 auto 4rem;
            }
        }

        /* Mobile */
        @media screen and (max-width: 768px) {
            .container {
                width: 100%;
                grid-template-columns: 1fr;
            }

            aside {
                position: fixed;
                left: -100%;
                background: var(--color-white);
                width: 18rem;
                z-index: 3;
                box-shadow: 1rem 3rem 4rem var(--color-light);
                height: 100vh;
                padding-right: var(--card-padding);
                display: none;
                animation: showMenu 400ms ease forwards;
            }

            @keyframes showMenu {
                to {
                    left: 0;
                }
            }

            aside .logo {
                margin-left: 1rem;
            }

            aside .sidebar h3 {
                display: inline;
            }

            aside .sidebar a {
                width: 100%;
                height: 3.9rem;
            }

            aside .sidebar a:last-child {
                position: absolute;
                bottom: 5rem;
            }

            aside .close {
                display: inline-block;
                cursor: pointer;
                padding-right: 1.8rem;
            }

            main {
                margin-top: 8rem;
                padding: 0 1rem;
            }

            main .recent-orders {
                position: relative;
                margin: 3rem 0;
                width: 100%;
            }

            .gestion-organisateurs {
                margin: 3rem 0; /* Adjust for mobile */
            }
            .gestion-organisateurs .dataTables_length,
            .gestion-organisateurs .dataTables_filter {
                flex-direction: column; /* Stack search and show entries */
                align-items: flex-start;
            }
            .gestion-organisateurs .dataTables_filter {
                align-self: flex-end; /* Push search to the right */
            }


            .right {
                width: 94%;
                margin: 0 auto 4rem;
            }

            .right .top {
                position: fixed;
                top: 0;
                left: 0;
                align-items: center;
                padding: 0 0.8rem;
                height: 4.6rem;
                background: var(--color-white);
                width: 100%;
                margin: 0;
                z-index: 2;
                box-shadow: 0 1rem 1rem var(--color-light);
            }

            .right .top .theme-toggler {
                width: 3.4rem;
                position: absolute;
                left: 6.5rem;
            }

            .right .profile .info {
                display: none;
            }

            .right .top button {
                display: inline-block;
                background: transparent;
                cursor: pointer;
                color: var(--color-dark);
                position: absolute;
                left: 1rem;
            }

            .right .top button span {
                font-size: 2rem;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <aside>
            <div class="top">
                <div class="logo">
                   <img src="/images/EVUP.gif" alt="EVUP Logo"> <h2>E<span class="danger">V</span>UP</h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">close</span>
                </div>
            </div>

            <div class="sidebar">
                <a href="#dashboard-content" class="sidebar-link active"> <span class="material-icons-sharp">dashboard</span>
                    <h3>Dashboard Admin</h3>
                </a>
                <a href="#organisateurs-content" class="sidebar-link"> <span class="material-icons-sharp">group</span>
                    <h3>Organisateurs</h3>
                </a>
                <a href="#evenements-content" class="sidebar-link">
                    <span class="material-icons-sharp">event</span>
                    <h3>Evenements</h3>
                </a>
                <a href="#participants-content" class="sidebar-link">
                    <span class="material-icons-sharp">person</span>
                    <h3>Participants</h3>
                </a>
                <a href="#commissions-content" class="sidebar-link">
                    <span class="material-icons-sharp">receipt_long</span>
                    <h3>Commissions</h3>
                </a>
                <a href="#statistiques-content" class="sidebar-link">
                    <span class="material-icons-sharp">insights</span>
                    <h3>Statistiques</h3>
                </a>
                <a href="#messages-content" class="sidebar-link">
                    <span class="material-icons-sharp">mail_outline</span>
                    <h3>Messages</h3>
                    <span class="message-count">26</span>
                </a>
                <a href="#parametres-content" class="sidebar-link">
                    <span class="material-icons-sharp">settings</span>
                    <h3>Parametres</h3>
                </a>
                <a href="#ajouter-content" class="sidebar-link">
                    <span class="material-icons-sharp">add</span>
                    <h3>Ajouter</h3>
                </a>
                <a href="#" class="sidebar-link">
                    <span class="material-icons-sharp">logout</span>
                    <h3>Se Deconnecter</h3>
                </a>
            </div>
        </aside>
        <main>
            <div id="dashboard-content" class="content-section active">
                <h1>Dashboard</h1>

                <div class="date">
                    <input type="date">
                </div>

                <div class="insights">
                    <div class="sales">
                        <span class="material-icons-sharp">confirmation_number</span>
                        <div class="middle">
                            <div class="left">
                                <h3>Billets Vendus</h3>
                                <h1>500</h1>
                            </div>
                            <div class="progress">
                                <svg>
                                    <circle cx='38' cy='38' r='36'></circle>
                                </svg>
                                <div class="number">
                                    <p>81%</p>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted">Last 24 Hours</small>
                    </div>
                    <div class="expenses">
                        <span class="material-icons-sharp">stacked_line_chart</span>
                        <div class="middle">
                            <div class="left">
                                <h3>Revenu Total</h3>
                                <h1>10 000 DH</h1>
                            </div>
                            <div class="progress">
                                <svg>
                                    <circle cx='38' cy='38' r='36'></circle>
                                </svg>
                                <div class="number">
                                    <p>62%</p>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted">Last 24 Hours</small>
                    </div>
                    <div class="income">
                        <span class="material-icons-sharp">date_range</span>
                        <div class="middle">
                            <div class="left">
                                <h3>Réservations</h3>
                                <h1>700</h1>
                            </div>
                            <div class="progress">
                                <svg>
                                    <circle cx='38' cy='38' r='36'></circle>
                                </svg>
                                <div class="number">
                                    <p>44%</p>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted">Last 24 Hours</small>
                    </div>
                    </div>

                <div class="recent-orders">
                    <h2>Événements Récents</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Nom Événement</th>
                                <th>Organisateur</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Festival de Jazz</td>
                                <td>Jazz Vibes Inc.</td>
                                <td>2025-07-15</td>
                                <td class="warning">En attente</td>
                                <td class="primary">Détails</td>
                            </tr>
                            <tr>
                                <td>Conférence Tech</td>
                                <td>Innovate Now</td>
                                <td>2025-06-20</td>
                                <td class="success">Terminé</td>
                                <td class="primary">Détails</td>
                            </tr>
                            <tr>
                                <td>Concert Pop</td>
                                <td>Music Masters</td>
                                <td>2025-08-01</td>
                                <td class="danger">Annulé</td>
                                <td class="primary">Détails</td>
                            </tr>
                             <tr>
                                <td>Salon de l'Auto</td>
                                <td>Auto World Expo</td>
                                <td>2025-09-10</td>
                                <td class="success">Actif</td>
                                <td class="primary">Détails</td>
                            </tr>
                             <tr>
                                <td>Exposition d'Art</td>
                                <td>Artistic Minds</td>
                                <td>2025-07-25</td>
                                <td class="warning">En attente</td>
                                <td class="primary">Détails</td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="#">Afficher Tout</a>
                </div>
            </div>

            <div id="organisateurs-content" class="content-section">
                <h1>Gestion des Organisateurs</h1>
                <div class="card">
                    <div class="card-header">
                        <h6>Liste des Organisateurs</h6>
                        </div>
                    <div class="list-link">
                        <a href="#">Liste des Organisateurs</a>
                    </div>
                    <div class="dataTables_wrapper">
                        <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
                            <div class="dataTables_length">
                                Show
                                <select>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                                entries
                            </div>
                            <div class="dataTables_filter">
                                Search: <input type="search">
                            </div>
                        </div>

                        <table class="dataTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Société</th>
                                    <th>Gérant</th>
                                    <th>Email</th>
                                    <th>ICE</th>
                                    <th>Statut Validation</th>
                                    <th>Statut Activité</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>maroc web</td>
                                    <td>basma basma</td>
                                    <td>basma@gmail.com</td>
                                    <td>11111</td>
                                    <td>
                                        <span class="badge badge-warning">En attente / Refusé</span>
                                        <button class="btn-sm btn-success ml-1"><i class="fas fa-check"></i></button>
                                    </td>
                                    <td>
                                        <span class="toggle-icon toggle-on"><i class="fas fa-toggle-on"></i></span>
                                    </td>
                                    <td>
                                        <button class="btn-sm btn-info mb-1"><i class="fas fa-eye"></i></button>
                                        <button class="btn-sm btn-warning mb-1"><i class="fas fa-edit"></i></button>
                                        <button class="btn-sm btn-danger mb-1"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>EVENT PRO MAROC</td>
                                    <td>Walid ALAQUI</td>
                                    <td>ahlam.benani@gmail.com</td>
                                    <td>001234567890123</td>
                                    <td>
                                        <span class="badge badge-success">Validé</span>
                                        <button class="btn-sm btn-danger ml-1"><i class="fas fa-times"></i></button>
                                    </td>
                                    <td>
                                        <span class="toggle-icon toggle-off"><i class="fas fa-toggle-off"></i></span>
                                    </td>
                                    <td>
                                        <button class="btn-sm btn-info mb-1"><i class="fas fa-eye"></i></button>
                                        <button class="btn-sm btn-warning mb-1"><i class="fas fa-edit"></i></button>
                                        <button class="btn-sm btn-danger mb-1"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="dataTables_info">Showing 1 to 2 of 2 entries</div>
                        <div class="dataTables_paginate">
                            <a class="paginate_button disabled">Previous</a>
                            <a class="paginate_button current">1</a>
                            <a class="paginate_button disabled">Next</a>
                        </div>
                    </div>
                </div>
            </div>

            <div id="evenements-content" class="content-section">
                <h1>Gestion des Événements</h1>
                <p>Contenu de la gestion des événements...</p>
            </div>
            <div id="participants-content" class="content-section">
                <h1>Gestion des Participants</h1>
                <p>Contenu de la gestion des participants...</p>
            </div>
             <div id="commissions-content" class="content-section">
                <h1>Gestion des Commissions</h1>
                <p>Contenu de la gestion des commissions...</p>
            </div>
            <div id="statistiques-content" class="content-section">
                <h1>Statistiques Détaillées</h1>
                <p>Contenu des statistiques...</p>
            </div>
            <div id="messages-content" class="content-section">
                <h1>Boîte de Réception</h1>
                <p>Contenu des messages...</p>
            </div>
            <div id="parametres-content" class="content-section">
                <h1>Paramètres du Compte</h1>
                <p>Contenu des paramètres...</p>
            </div>
            <div id="ajouter-content" class="content-section">
                <h1>Ajouter Nouveau</h1>
                <p>Formulaire pour ajouter un nouvel élément (organisateur, événement, etc.)...</p>
            </div>


        </main>
        <div class="right">
            <div class="top">
                <button id="menu-btn">
                    <span class="material-icons-sharp">menu</span>
                </button>
                <div class="theme-toggler">
                    <span class="material-icons-sharp active">light_mode</span>
                    <span class="material-icons-sharp">dark_mode</span>
                </div>
                <div class="profile">
                    <div class="info">
                        <p>Hey, <b>Admin</b></p>
                        <small class="text-muted">Administrateur</small>
                    </div>
                    <div class="profile-photo">
                        <img src="/images/admin-profile.png" alt="Admin Profile Pic"> </div>
                </div>
            </div>
            <div class="recent-updates">
                <h2>Mises à jour Récentes</h2>
                <div class="updates">
                    <div class="update">
                        <div class="profile-photo">
                            <img src="/images/profile-1.png"> </div>
                        <div class="message">
                            <p><b>John Doe</b> a validé l'organisateur 'Tech Solutions'.</p>
                            <small class="text-muted">2 Minutes Ago</small>
                        </div>
                    </div>
                    <div class="update">
                        <div class="profile-photo">
                            <img src="/images/profile-2.png"> </div>
                        <div class="message">
                            <p><b>Jane Smith</b> a refusé l'événement 'Gaming Expo'.</p>
                            <small class="text-muted">5 Minutes Ago</small>
                        </div>
                    </div>
                    <div class="update">
                        <div class="profile-photo">
                            <img src="/images/profile-3.png"> </div>
                        <div class="message">
                            <p><b>Mike Johnson</b> a commenté sur l'événement 'Summer Music Fest'.</p>
                            <small class="text-muted">10 Minutes Ago</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sales-analytics">
                <h2>Analyses des Ventes</h2>
                <div class="item online">
                    <div class="icon">
                        <span class="material-icons-sharp">shopping_cart</span>
                    </div>
                    <div class="right">
                        <div class="info">
                            <h3>Ventes en ligne</h3>
                            <small class="text-muted">Dernières 24 Heures</small>
                        </div>
                        <h5 class="success">+39%</h5>
                        <h3>3849</h3>
                    </div>
                </div>
                <div class="item offline">
                    <div class="icon">
                        <span class="material-icons-sharp">local_mall</span>
                    </div>
                    <div class="right">
                        <div class="info">
                            <h3>Ventes Hors Ligne</h3>
                            <small class="text-muted">Dernières 24 Heures</small>
                        </div>
                        <h5 class="danger">-17%</h5>
                        <h3>1100</h3>
                    </div>
                </div>
                <div class="item customers">
                    <div class="icon">
                        <span class="material-icons-sharp">person</span>
                    </div>
                    <div class="right">
                        <div class="info">
                            <h3>Nouveaux Clients</h3>
                            <small class="text-muted">Dernières 24 Heures</small>
                        </div>
                        <h5 class="success">+25%</h5>
                        <h3>849</h3>
                    </div>
                </div>
                <div class="add-product">
                    <div>
                        <span class="material-icons-sharp">add</span>
                        <h3>Ajouter un Produit</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // SIDEBAR TOGGLE
        const sideMenu = document.querySelector("aside");
        const menuBtn = document.querySelector("#menu-btn");
        const closeBtn = document.querySelector("#close-btn");

        menuBtn.addEventListener('click', () => {
            sideMenu.style.display = 'block';
        });

        closeBtn.addEventListener('click', () => {
            sideMenu.style.display = 'none';
        });

        // THEME TOGGLE
        const themeToggler = document.querySelector(".theme-toggler");
        const body = document.body;

        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark-theme-variables') {
            body.classList.add('dark-theme-variables');
            themeToggler.querySelector('span:nth-child(2)').classList.add('active'); // Dark mode icon
            themeToggler.querySelector('span:nth-child(1)').classList.remove('active'); // Light mode icon
        } else {
            // Default to light theme
            themeToggler.querySelector('span:nth-child(1)').classList.add('active'); // Light mode icon
            themeToggler.querySelector('span:nth-child(2)').classList.remove('active'); // Dark mode icon
        }

        themeToggler.addEventListener('click', () => {
            body.classList.toggle('dark-theme-variables');

            themeToggler.querySelector('span:nth-child(1)').classList.toggle('active');
            themeToggler.querySelector('span:nth-child(2)').classList.toggle('active');

            // Save theme preference to localStorage
            if (body.classList.contains('dark-theme-variables')) {
                localStorage.setItem('theme', 'dark-theme-variables');
            } else {
                localStorage.setItem('theme', 'light-theme-variables');
            }
        });

        // Set current date in the date input
        const dateInput = document.querySelector('main .date input[type="date"]');
        if (dateInput) {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
            const day = String(today.getDate()).padStart(2, '0');
            dateInput.value = `${year}-${month}-${day}`;
        }


        // Handle sidebar active link AND content display
        document.addEventListener('DOMContentLoaded', () => {
            const sidebarLinks = document.querySelectorAll('aside .sidebar .sidebar-link');
            const contentSections = document.querySelectorAll('main .content-section');

            // Function to show a specific section
            function showSection(sectionId) {
                contentSections.forEach(section => {
                    section.classList.remove('active');
                });
                const targetSection = document.getElementById(sectionId);
                if (targetSection) {
                    targetSection.classList.add('active');
                }
            }

            // Set initial active link and content
            // By default, 'dashboard-content' is active in HTML, so also make its link active
            const initialActiveLink = document.querySelector('a[href="#dashboard-content"]');
            if (initialActiveLink) {
                initialActiveLink.classList.add('active');
            }


            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent default link behavior (page reload/anchor jump)

                    // Remove active class from all sidebar links
                    sidebarLinks.forEach(item => item.classList.remove('active'));
                    // Add active class to the clicked link
                    this.classList.add('active');

                    // Get the ID of the content section to show from the href
                    const targetId = this.getAttribute('href').substring(1); // Remove '#'
                    showSection(targetId);
                });
            });

            // Ensure the initial dashboard content is displayed on load
            showSection('dashboard-content');
        });

    </script>
</body>
</html>