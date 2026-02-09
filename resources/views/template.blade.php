 <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 120px 50px 80px 50px;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        
        .header {
            position: fixed;
            top: -100px;
            left: 0;
            right: 0;
            height: 100px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            padding-left: 50px;
            padding-right: 50px;
        }
        
        .header-content {
            display: table;
            width: 100%;
        }
        
        .header-left {
            display: table-cell;
            width: 90px;
            vertical-align: middle;
            padding-right: 15px;
        }
        
        .header-center {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
            padding-left: 0;
        }
        
        .institution-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            padding: 0;
        }
        
        .institution-details {
            font-size: 10px;
            margin: 5px 0 0 0;
        }
        
        .footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            height: 50px;
            border-top: 1px solid #333;
            padding-top: 10px;
            font-size: 10px;
            padding-left: 50px;
            padding-right: 50px;
        }
        
        .content {
            margin-top: 20px;
            padding: 0 40px;
        }
        
        .document-title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
            text-transform: uppercase;
            padding: 0;
        }
        
        .generated-date {
            font-size: 11px;
            text-align: center;
            margin-bottom: 20px;
            color: #666;
        }
        
        .document-content {
            text-align: justify;
            margin-top: 20px;
            padding: 0;
            line-height: 1.8;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="header-left">{!! $logoHtml !!}</div>
            <div class="header-center">
                <h1 class="institution-name">{{ $institutionName }}</h1>
                <div class="institution-details">
                    {{ $address }}
                    @if(!empty($phone))<br>Telp: {{ $phone }}@endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <!-- Footer content rendered by DomPDF canvas -->
    </div>
    
    <div class="content">
        <div class="document-title">{{ $title }}</div>
        <div class="generated-date">Tanggal Generate: {{ $generatedDate }}</div>
        <div class="document-content">
            {!! nl2br(e($content)) !!}
        </div>
    </div>
</body>
</html>