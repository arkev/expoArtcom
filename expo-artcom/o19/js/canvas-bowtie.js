
jQuery(function(){
		var elements = jQuery(".bowtie").each(function(i,e){
			draw(e);
		});
	}
);

function draw(element) {

	ctx = element.getContext("2d");
	var gradient;

	// layer1/Compound Path
	ctx.save();
	ctx.beginPath();

	// layer1/Compound Path/Group

	// layer1/Compound Path/Group/Path
	ctx.save();
	ctx.moveTo(21.5, 8.5);
	ctx.lineTo(21.5, 8.5);
	ctx.lineTo(21.5, 8.5);
	ctx.lineTo(19.2, 9.4);
	ctx.lineTo(5.5, 3.5);
	ctx.lineTo(5.5, 3.5);
	ctx.lineTo(2.0, 2.0);
	ctx.lineTo(2.0, 0.0);
	ctx.lineTo(5.5, 1.5);
	ctx.lineTo(5.5, 1.5);
	ctx.lineTo(21.5, 8.4);
	ctx.lineTo(21.5, 8.4);
	ctx.lineTo(21.5, 8.5);
	ctx.closePath();

	// layer1/Compound Path/Group/Path
	ctx.moveTo(37.5, 1.5);
	ctx.lineTo(37.5, 1.5);
	ctx.lineTo(21.6, 8.4);
	ctx.lineTo(21.6, 8.4);
	ctx.lineTo(21.5, 8.5);
	ctx.lineTo(21.6, 8.5);
	ctx.lineTo(21.6, 8.5);
	ctx.lineTo(23.8, 9.4);
	ctx.lineTo(37.5, 3.5);
	ctx.lineTo(37.5, 3.5);
	ctx.lineTo(41.1, 2.0);
	ctx.lineTo(41.1, 0.0);
	ctx.lineTo(37.5, 1.5);
	ctx.closePath();

	// layer1/Compound Path/Group/Path
	ctx.moveTo(5.5, 15.4);
	ctx.lineTo(2.0, 16.9);
	ctx.lineTo(2.0, 18.9);
	ctx.lineTo(5.5, 17.4);
	ctx.lineTo(5.5, 17.3);
	ctx.lineTo(21.5, 10.5);
	ctx.lineTo(21.5, 10.5);
	ctx.lineTo(21.5, 10.4);
	ctx.lineTo(21.5, 10.4);
	ctx.lineTo(21.5, 10.4);
	ctx.lineTo(19.2, 9.4);
	ctx.lineTo(5.5, 15.4);
	ctx.lineTo(5.5, 15.4);
	ctx.closePath();

	// layer1/Compound Path/Group/Path
	ctx.moveTo(3.6, 3.5);
	ctx.lineTo(0.0, 2.0);
	ctx.lineTo(0.0, 16.9);
	ctx.lineTo(3.6, 15.4);
	ctx.lineTo(3.6, 15.4);
	ctx.lineTo(17.3, 9.4);
	ctx.lineTo(3.6, 3.5);
	ctx.lineTo(3.6, 3.5);
	ctx.closePath();

	// layer1/Compound Path/Group/Path
	ctx.moveTo(37.5, 15.4);
	ctx.lineTo(23.8, 9.4);
	ctx.lineTo(21.6, 10.4);
	ctx.lineTo(21.6, 10.4);
	ctx.lineTo(21.5, 10.4);
	ctx.lineTo(21.6, 10.5);
	ctx.lineTo(21.6, 10.5);
	ctx.lineTo(37.5, 17.3);
	ctx.lineTo(37.5, 17.4);
	ctx.lineTo(41.1, 18.9);
	ctx.lineTo(41.1, 16.9);
	ctx.lineTo(37.5, 15.4);
	ctx.lineTo(37.5, 15.4);
	ctx.closePath();

	// layer1/Compound Path/Group/Path
	ctx.moveTo(39.5, 3.5);
	ctx.lineTo(25.8, 9.4);
	ctx.lineTo(39.5, 15.4);
	ctx.lineTo(39.5, 15.4);
	ctx.lineTo(43.0, 16.9);
	ctx.lineTo(43.0, 2.0);
	ctx.lineTo(39.5, 3.5);
	ctx.lineTo(39.5, 3.5);
	ctx.closePath();
	gradient = ctx.createLinearGradient(21.4, -0.3, 21.7, 21.5);
	gradient.addColorStop(0.58, "rgb(5, 5, 5)");
	gradient.addColorStop(0.71, "rgb(22, 22, 22)");
	gradient.addColorStop(1.00, "rgb(40, 40, 40)");
	ctx.fillStyle = gradient;
	ctx.fill();
	ctx.restore();
	ctx.restore();
}