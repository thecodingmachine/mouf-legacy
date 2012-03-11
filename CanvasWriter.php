<?php
/*
 * This file is part of the Mouf core package.
 *
 * (c) 2012 David Negrier <david@mouf-php.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */
 


class CanvasWritter {
	
	public function getInstanceCanvas($instanceName) {
		$instance = MoufManager::getMoufManager()->getInstance($instanceName);
		$className = get_class($instance);
		$str = "
ctx.save();
ctx.beginPath();
ctx.fillStyle = '#F7FB9B';
ctx.strokeStyle = '#EEE303';
ctx.moveTo(0,0);
ctx.lineTo(140,0);
ctx.lineTo(150,10);
ctx.lineTo(150,200);
ctx.lineTo(0,200);
ctx.lineTo(0,0);
ctx.moveTo(0,36);
ctx.lineTo(150,36);
ctx.fill();
ctx.stroke();

ctx.translate(5, 16);
ctx.fillStyle = '#EEE303';
ctx.mozDrawText('$instanceName');
ctx.translate(10, 16);
ctx.fillStyle = '#EEE303';
ctx.mozDrawText('\"$className\"');
ctx.restore();
		";
		
		return $str;
	}
}
?>