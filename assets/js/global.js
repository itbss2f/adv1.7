/* File generated by shrinker.ch - DateTime: 2013-01-28, 09:16:02 */
$(document).ready(function(){$(window).resize(function(){$(window).width()<960?$(".fixed .inner").addClass("remove"):$(".fixed .inner").removeClass("remove")});$("#toggle_menu").click(function(){$(this).toggleClass("menu_click");var a=$(this);$("#nav").slideToggle("fast",function(){a.hasClass("menu_click")?$("#hdmenu .inner").addClass("remove"):$("#hdmenu .inner").removeClass("remove")})})});
$(function(){$.fn.hoverable=function(a){$(this).hover(function(){$(this).addClass("hover")},function(){$(this).removeClass("hover")}).click(function(){$(".tbody.selected",$(this).parent()).removeClass("selected");$(this).addClass("selected");a.select&&a.select(this)})}});
