<!DOCTYPE html>
<html>
<head>
    <title>QR Codes for Students</title>
    <style>
* {
  box-sizing: border-box;
}

.column {
  float: left;
  width: 10%;
  padding: 5px;
}


/* Clearfix (clear floats) */

.row::after {
  content: "";
  clear: both;
  display: table;
}
.a4-container {
    width: 210mm; /* A4 width */
    height: 297mm; /* A4 height */
    padding: 20px; /* Optional: padding inside the container */
    box-sizing: border-box; /* Ensures padding is included in total width and height */
    margin: 0 auto; /* Center the container if needed */
}
@media print {
    .a4-container {
        width: 210mm; /* A4 width */
        height: 297mm; /* A4 height */
        page-break-after: always; /* Ensures a new page for printing */
    }
}

</style>
</head>
<body>

<div class="a4-container">
</br></br></br>
    <div class="row">

    @foreach ($qrCodes as $data)
        <div>
        <div class="column">
            <p style="font-size: 10px;">{{ $data['student_name'] }}</p>
            {!! $data['qr_code'] !!}
        </div>
        </div>
    @endforeach
   
    </div>
 </div>
</body>
</html>
