<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Ticket de Estacionamiento</title>
  <style>
    body {
      font-family: 'Courier New', Courier, monospace;
      background-color: #1b3b6f; /* Fondo azul */
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      flex-direction: column;
    }

    .ticket {
      width: 300px;
      background: #fff;
      padding: 20px;
      text-align: center;
      border: 2px solid #000;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .company {
      text-align: right;
      font-size: 12px;
      margin-bottom: 10px;
    }

    .ticket img.icon-car {
      width: 60px;
      margin-bottom: 10px;
    }

    .dashed {
      border-top: 1px dashed black;
      margin: 10px 0;
    }

    .title {
      font-size: 18px;
      font-weight: bold;
      letter-spacing: 2px;
    }

    .date {
      font-size: 16px;
      margin: 5px 0;
    }

    .info {
      margin: 10px 0;
      font-size: 14px;
    }

    .info img {
      width: 30px;
      vertical-align: middle;
    }

    .info span {
      margin: 0 5px;
    }

    .barcode {
      margin: 15px 0;
    }

    .barcode img {
      width: 100%;
      height: 40px;
      object-fit: contain;
    }

    .thanks {
      font-size: 13px;
      margin-top: 5px;
    }

    .print-button {
      margin-top: 20px;
    }

    @media print {
      body {
        background-color: white;
      }

      .print-button {
        display: none;
      }
    }
  </style>
</head>
<body>
  <div class="ticket" id="ticket">
    <div class="company">
      <div>{{$cochera->nombre}}</div>
      <div>{{$cochera->direccion}}</div>
    </div>
    <img class="icon-car" src="{{$logoEmpresa == ""?"https://img.icons8.com/ios-filled/100/car--v1.png":"$logoEmpresa"}}" alt="Car Icon">
    <div class="dashed"></div>
    <div class="title">COMPROBANTE DE ESTACIONAMIENTO</div>
    <div class="date">{{$marcacion->fechaentrada}}</div>

    <div class="info">
      @if($marcacion->nombre == 'moto')
          <img src="https://img.icons8.com/ios-filled/50/motorcycle.png">
      @else
          <img src="https://img.icons8.com/ios-filled/50/car.png">
      @endif
      <span>→</span>
      <span>DESDE : {{$marcacion->horaentrada}}</span>
    </div>
    <div class="info">
      PLACA: {{strtoupper($marcacion->placa)}}
    </div>
    @if ($marcacion->fecha_salida == null)
        <div class="dashed"></div>
        <div class="info">
          <strong>ESTADO:</strong> EN CURSO
        </div>
    @else 
    <div class="dashed"></div>
      <div class="date">{{$marcacion->fechasalida}}</div>
      <div class="info">
        HASTA LAS : {{$marcacion->horasalida}}
        <span>→</span>
        @if($marcacion->nombre == 'moto')
            <img src="https://img.icons8.com/ios-filled/50/motorcycle.png">
        @else
            <img src="https://img.icons8.com/ios-filled/50/car.png">
        @endif
      </div>

      <div class="info" style="margin-top: 10px;">
        <strong>Pagado :</strong> {{$marcacion->tipomoneda == "PEN"?"S/ ":"$ "}} {{$marcacion->monto_total}}
      </div>      
    @endif

    <div class="dashed"></div>

    <div class="barcode">
      {!! QrCode::size(120)->generate($urlCodigo) !!}
    </div>

    <div class="thanks">¡GRACIAS Y BUENA SUERTE EN EL CAMINO!</div>
  </div>

  <button class="print-button" onclick="window.print()">Imprimir Ticket</button>
</body>
</html>
