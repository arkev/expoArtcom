
jQuery(function(){
		var elements = jQuery(".bowtie-autores").each(function(i,e){
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
  ctx.moveTo(12.0, 4.7);
  ctx.lineTo(12.0, 4.7);
  ctx.lineTo(12.0, 4.7);
  ctx.lineTo(10.7, 5.3);
  ctx.lineTo(3.1, 2.0);
  ctx.lineTo(3.1, 2.0);
  ctx.lineTo(1.1, 1.1);
  ctx.lineTo(1.1, -0.0);
  ctx.lineTo(3.1, 0.9);
  ctx.lineTo(3.1, 0.9);
  ctx.lineTo(12.0, 4.7);
  ctx.lineTo(12.0, 4.7);
  ctx.lineTo(12.0, 4.7);
  ctx.closePath();

  // layer1/Compound Path/Group/Path
  ctx.moveTo(20.9, 0.9);
  ctx.lineTo(20.9, 0.9);
  ctx.lineTo(12.0, 4.7);
  ctx.lineTo(12.0, 4.7);
  ctx.lineTo(12.0, 4.7);
  ctx.lineTo(12.0, 4.7);
  ctx.lineTo(12.0, 4.7);
  ctx.lineTo(13.3, 5.3);
  ctx.lineTo(20.9, 2.0);
  ctx.lineTo(20.9, 2.0);
  ctx.lineTo(22.9, 1.1);
  ctx.lineTo(22.9, -0.0);
  ctx.lineTo(20.9, 0.9);
  ctx.closePath();

  // layer1/Compound Path/Group/Path
  ctx.moveTo(3.1, 8.6);
  ctx.lineTo(1.1, 9.4);
  ctx.lineTo(1.1, 10.6);
  ctx.lineTo(3.1, 9.7);
  ctx.lineTo(3.1, 9.7);
  ctx.lineTo(12.0, 5.8);
  ctx.lineTo(12.0, 5.8);
  ctx.lineTo(12.0, 5.8);
  ctx.lineTo(12.0, 5.8);
  ctx.lineTo(12.0, 5.8);
  ctx.lineTo(10.7, 5.3);
  ctx.lineTo(3.1, 8.6);
  ctx.lineTo(3.1, 8.6);
  ctx.closePath();

  // layer1/Compound Path/Group/Path
  ctx.moveTo(2.0, 2.0);
  ctx.lineTo(0.0, 1.1);
  ctx.lineTo(0.0, 9.4);
  ctx.lineTo(2.0, 8.6);
  ctx.lineTo(2.0, 8.6);
  ctx.lineTo(9.6, 5.3);
  ctx.lineTo(2.0, 2.0);
  ctx.lineTo(2.0, 2.0);
  ctx.closePath();

  // layer1/Compound Path/Group/Path
  ctx.moveTo(20.9, 8.6);
  ctx.lineTo(13.3, 5.3);
  ctx.lineTo(12.0, 5.8);
  ctx.lineTo(12.0, 5.8);
  ctx.lineTo(12.0, 5.8);
  ctx.lineTo(12.0, 5.8);
  ctx.lineTo(12.0, 5.8);
  ctx.lineTo(20.9, 9.7);
  ctx.lineTo(20.9, 9.7);
  ctx.lineTo(22.9, 10.6);
  ctx.lineTo(22.9, 9.4);
  ctx.lineTo(20.9, 8.6);
  ctx.lineTo(20.9, 8.6);
  ctx.closePath();

  // layer1/Compound Path/Group/Path
  ctx.moveTo(22.0, 2.0);
  ctx.lineTo(14.4, 5.3);
  ctx.lineTo(22.0, 8.6);
  ctx.lineTo(22.0, 8.6);
  ctx.lineTo(24.0, 9.4);
  ctx.lineTo(24.0, 1.1);
  ctx.lineTo(22.0, 2.0);
  ctx.lineTo(22.0, 2.0);
  ctx.closePath();
  gradient = ctx.createLinearGradient(11.9, -0.1, 12.1, 12.0);
  gradient.addColorStop(0.58, "rgb(5, 5, 5)");
  gradient.addColorStop(0.71, "rgb(22, 22, 22)");
  gradient.addColorStop(1.00, "rgb(40, 40, 40)");
  ctx.fillStyle = gradient;
  ctx.fill();
  ctx.restore();
  ctx.restore();
}