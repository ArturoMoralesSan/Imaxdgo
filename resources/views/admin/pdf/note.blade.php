<!DOCTYPE html>
<html>
<head>
    <title>Nota de venta IMAX</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            background-image: url('img/Nota-de-Venta-IMAX.png'); /* Ruta a tu imagen de fondo */
            background-repeat: no-repeat;
            background-size:contain;
            position: relative; 
            font-family: Arial, sans-serif; /* Cambiar la familia de fuentes a Arial */
            font-size: 15px; 
        }

        .container-folio {
            position: absolute;
            top: 42px; /* Posición superior */
            right: 120px; /* Posición izquierda */
            
        }
        .date {
            position: absolute;
            top: 75px; /* Posición superior */
            right: 50px; /* Posición izquierda */
            
        }
        .year {
            margin-right:-35px;        
        }
        .month {
         margin-left:15px;
        }
        .month {
            margin-right:17px;        
        }

        .container-patient {
            position: absolute;
            top: 118px; /* Posición superior */
            left: 100px; /* Posición izquierda */
        }
        .container-service {
            position: absolute;
            top: 190px; /* Posición superior */
            left: 70px;
            right:0;
            font-size:20px;
        }

        .service-qty {
            display: inline-block;
            position absolute;
            margin-right:60px;
            
        }

        .service-list {
            display: inline-block;
            word-wrap: break-word;
            width:340px;
            
        }

        .service-import {
            display: inline-block;            
            
        }
        .service-cost {
            display: inline-block;
            margin-right:55px;
            margin-left:5px;
        }

        .container-letter {
            position: absolute;
            bottom: 40px; /* Posición superior */
            left: 45px;
            right:0;
            font-size:15px;
            
        }

        .letter-cost {
            display: inline-block;
            width: 440px;
            margin-right:105px;
        }
        .container-total {
            display: inline-block;
        }

        .total-total {
            margin-top:20px;
            margin-bottom:-20px;
        }

        
    </style>
</head>
<body>
    <div class="container-folio">
        <span class="folio"> {{ $service->id }}</span>
    </div>
    <div class="date">
        <span class="day">
            {{ $service->day }}
        </span>
        <span class="month">
            {{ $service->month }}
        </span>
        <span class="year">
            {{ $service->year }}
        </span>
    </div>
    <div class="container-patient">
        <span class="patient">
            {{ $service->patient }}
        </span>
    </div>
    <div class="container-service">
       <div class="service-qty">
                1
       </div>
       <div class="service-list">
            {{ $service->list_studies }}
       </div>
       <div class="service-cost">
            ${{ $service->cost }}
       </div>
       <div class="service-import">
            ${{ $service->cost }}
       </div>
    </div>
    <div class="container-letter">
       <div class="letter-cost">
            {{ $service->letter }}
       </div>
       <div class="container-total">
            <div class="total-subtotal">
                ${{ $service->cost }}
            </div>
            <div class="total-total">
                ${{ $service->cost }}
            </div>
       </div>
      
       
    </div>
</body>
</html>