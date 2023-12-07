<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="jqwidgets/styles/jqx.base.css" type="text/css" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1 minimum-scale=1" />
    <script type="text/javascript" src="scripts/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxinput.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxdata.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxmenu.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.edit.js"></script>  
    <script type="text/javascript" src="jqwidgets/jqxgrid.columnsresize.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxgrid.sort.js"></script>  
    <script type="text/javascript" src="jqwidgets/jqxgrid.selection.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxlistbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcheckbox.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxcalendar.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxnumberinput.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdatetimeinput.js"></script>
    <script type="text/javascript" src="jqwidgets/globalization/globalize.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxsplitter.js"></script>
    <script type="text/javascript" src="jqwidgets/jqxdata.export.js"></script> 
    <script type="text/javascript" src="jqwidgets/jqxgrid.export.js"></script> 
    <style type="text/css">
        .editedRow {
          color: #b90f0f !important;
          font-style: italic;
        }
        #jqxWidget{
            width:100%;
            height:100%;
        }
        #jqxWidget1{
            width:100%;
            height:100%;
        }
        html, body 
        {
            height: 100%;
            width: 100%;
            margin: 0px;
            padding: 0px;
            overflow: hidden;
        }
    </style>
</head>
<body class='default' style="padding:10%; padding-top:5%">
    <table height="100%" width="80%">
		<tr>
			<td style="font-size:36px; text-align:center">AI Model for MySQL Database</td>
		</tr>
        <tr height="35px">
            <td width="100%">
				<table width="100%">
					<tr>
						<td width="100%">
							<input id="jqxInput" style="padding-left:10px; padding-right:10px;"/>
						</td>
						<td width="100">
							<input id="clear_btn" type="button" value="Clear" style="height:35px">
						</td>
					</tr>
				</table>
            </td>
        </tr>
        <tr height="80%">
            <td style="vertical-align:top">
                <div id="grid_answer"></div>
				<div style="font-size: 12px; font-family: Verdana, Geneva, 'DejaVu Sans', sans-serif; margin-top: 30px;">
					<div id="cellbegineditevent"></div>
					<div style="margin-top: 10px;" id="cellendeditevent"></div>
				</div>
            </td>
        </tr>
    </table>
</body>
</html>

<script type="text/javascript">

var answer_data = new Array();
$("#jqxInput").jqxInput({ placeHolder: "Please enjoy to input your QUESTION and press ENTER.", width: '100%', height: 35 });
$('#jqxInput').on ('keypress', function (e) {
	if (e.keyCode == 13 || e.which == 13) {
		//alert($('#jqxInput').jqxInput('val'));
		getResult();
	}
});
var sourceAnswer =
{
	 localdata: answer_data,
	 datatype: "array",
	 datafields: []
};
var dataAdapterAnswer = new jQuery.jqx.dataAdapter(sourceAnswer);
var columnData = [];
$("#grid_answer").jqxGrid(
{
	 width: '100%',
	 height: '60%',
	 source: dataAdapterAnswer,
	 columnsresize: true,
	 editable: false,
	 rowsheight: 25,
	 selectionmode: 'singlerow',
	 columns: columnData
});
$("#clear_btn").click(function(event) {

	event.preventDefault();
});

async function getResult() {
    setLoading(true);
    
    let query = $('#jqxInput').jqxInput('val');
    
    const { status1, message1 } = await fetch("ajax_response.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ request_type:'getAvailable', question: query }),
    }).then((r) => r.json());
    
	if(status1 == "failed")
	{
	    alert(message1);
		setLoading(false);
		return;
	}

	const { status, message } = await fetch("ajax_response.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ request_type:'getAvailable', question: query }),
    }).then((r) => r.json());
    
	if(status == "success")
	{
		if(message.length > 0)
		{
			sourceAnswer.datafields.length = 0;
			columnData.length = 0;
			
			for(var key in message[0])
			{
				sourceAnswer.datafields.push({name: key, type: 'string'});
				columnData.push({ text: key, datafield: key, columntype: 'textbox', align: 'center', cellsalign: 'left', width: 100});
			}

			answer_data.length = 0;
			for(var i in message)
				answer_data.push(message[i]);
			jQuery("#grid_answer").jqxGrid('updatebounddata', 'cells');
		}
		else
			alert("I am soory, but I have found no answers for your question.");
	}
	else
		alert(message);
	setLoading(false);
}

function setLoading(isLoading) {
	jQuery("#jqxInput").jqxInput({disabled: isLoading});
}
</script>
