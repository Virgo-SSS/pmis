<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding: 10px 0;
            background-color: #4CAF50; /* For approval */
            color: white;
            border-radius: 8px 8px 0 0;
        }
        .header.reject {
            background-color: #dc3545; /* For rejection */
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .content p {
            font-size: 16px;
            color: #333;
            line-height: 1.5;
        }
        .btn {
            display: inline-block;
            margin: 20px 0;
            padding: 12px 24px;
            font-size: 16px;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn.approve {
            background-color: #4CAF50;
        }
        .btn.reject {
            background-color: #dc3545;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #777777;
            padding: 10px;
            border-top: 1px solid #eeeeee;
            margin-top: 20px;
        }
    </style>
</head>
<body>

@if($status)
<div class="container">
    <!-- APPROVED Notification -->
    <div class="header approve">
        <h2>Leave Request Approved</h2>
    </div>

    <div class="content">
        <p>Hello {{ $leave->user->name }},</p>
        <p>We are pleased to inform you that your leave request from <strong>{{ $leave->start_date->format("d F Y") }}</strong> to <strong>{{ $leave->end_date->format("d F Y") }}</strong> has been <strong>approved</strong>.</p>
        <p>If you have any further queries, please contact the HR department.</p>
    </div>

    <div class="footer">
        <p>&copy; 2024 PMIS Company. All Rights Reserved.</p>
    </div>
</div>
@endif

@if(!$status)
<!-- REJECTED Notification -->
<div class="container">
    <div class="header reject">
        <h2>Leave Request Rejected</h2>
    </div>

    <div class="content">
        <p>Hello {{ $leave->user->name }},</p>
        <p>We regret to inform you that your leave request from <strong>{{ $leave->start_date->format("d F Y") }}</strong> to <strong>{{ $leave->end_date->format("d F Y") }}</strong> has been <strong>rejected</strong>.</p>
        <p>Please contact the HR department if you need more details or further assistance.</p>
    </div>

    <div class="footer">
        <p>&copy; 2024 PMIS Company. All Rights Reserved.</p>
    </div>
</div>
@endif
</body>
</html>
