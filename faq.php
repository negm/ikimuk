<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/class/settings.php';
$settings = new settings();
$pagetitle="ikimuk | FAQ";
include $_SERVER["DOCUMENT_ROOT"]."/block/header.php";

include $_SERVER["DOCUMENT_ROOT"]."/block/top_area.php";
 ?>
<div class="body">
    <div class="body_content">


        <div class="page_header">
Frequently asked questions (FAQ)
</div>

<h2 id="sec1" class="faq_title">About ikimuk</h2><hr class="faq_line" /> 

<div>
	<h4 class="question">
		How does it work?
	</h4>

	<div class="answer">
		<ol>
			<li> Order your favorite T-shirt design from the competition. </li>
  <li> If that design reaches a minimum of <?php echo $settings->goals[0]; ?> orders, we print it. </li>
			<li> We then deliver the T-shirt to your front door. </li>
		</ol>
	</div> 
</div>

<div>
	<h4 class="question">
		Can I order more than one design?
	</h4>

	<div class="answer">
		<p>
			Of course you can. You can order as many T-shirt designs as you'd like, as long as you pay for them in the event that they all get printed.
		</p> 
	</div>
</div>

<div>
	<h4 class="question">
		What happens if my favorite design doesn't reach <? echo $settings->goals[0]; ?> orders?
	</h4>

	<div class="answer">
		<p>
			If a design doesn't reach the minimum of <? echo $settings->goals[0]; ?> orders, it will not get printed. It will only be showcased in our "Past Competitions" section.
		</p>
	</div>
</div> 


<div>
	<h4 class="question">
		How can I help my favorite design get printed?
	</h4>

	<div class="answer">
		<ol>
			<li>Tweet about it</li>
			<li>Share it</li>
			<li>Mention it on your blog</li>
			<li>Tell your friends about it!</li>
		</ol>
	</div> 
</div>


<div>
	<h4 class="question">
		Why have competitions? Why can't you just sell all the T-shirts?
	</h4>

	<div class="answer">
		<p>
			Competitions are a fun and engaging way to ensure that you all love what we sell.
		</p>
	</div>
</div>

<div>
	<h4 class="question">
		How often do you come out with new designs?
	</h4>

	<div class="answer">
		<p>
			We put up a new batch of designs every two weeks.
		</p>
	</div>
</div>

<div>
	<h4 class="question">
		Is there an ikimuk store?
	</h4>

	<div class="answer">
		<p>
			Nope. We're currently only operating online, but you may stumble upon concept stores and boutiques selling handfuls of our tees.
		</p>
	</div>
</div>


<div>
	<h4 class="question">
		How do I contact the ikimuk team? 
	</h4>

	<div class="answer">
		<p>
			You can contact us through <a href="mailto:hello@ikimuk.com" target="_blank">hello@ikimuk.com</a> with any queries, concerns or suggestions you might have.
		</p>
	</div>
</div> 

<h2 id="sec2" class="faq_title">Payment</h2><hr class="faq_line" />

<div>
	<h4 class="question">
		How do I pay?
	</h4>

	<div class="answer">
		<p>
			You can pay online using your credit card. We have partnered with Bank Audi to ensure that you can shop online in a comfortable, safe and reliable way.
		</p>
	</div>
</div>

<div>
	<h4 class="question">
		How much is a T-shirt?
	</h4>

	<div class="answer">
		<p>
			Our T-shirts are $25 each.
		</p>
	</div>
</div>

<h2 class="faq_title">Design Submissions</h2><hr class="faq_line" />

<div>
	<h4 class="question">
		Do I keep the rights to my artwork?
	</h4>

	<div class="answer">
		<p>
			If your design is accepted for the competition, it is considered commissioned work, done exclusively for the purpose of the contest. So while we retain full rights over it, you will always be credited as the artist.
		</p>

		<p>
			If your design isn't accepted, you keep all rights over your design.
		</p>
	</div>
</div>


<div>
	<h4 class="question">
		How do I submit a design?
	</h4>


	<div class="answer">
		<p>
			You can submit your design by going to the 'Submit page' and following the simple guidelines. It's really easy, we promise!
		</p>
	</div>
</div>


<div>
	<h4 class="question">
		How long does it take for a submission to go up?
	</h4>

	<div class="answer">
		<p>
			If you are submitting to a themed competition, your design will go up when the competition is scheduled to start.
		</p>

		<p>
			If you are submitting to an open competition, we will put your design up  when there are a sufficient number of designs in that competition.
		</p>
	</div>
</div>


<div>
	<h4 class="question">
		How am I paid if my design is picked?
	</h4>

	<div class="answer">
		<p>
			We will handle payment on a case by case basis. Depending on your country, we can give you a check or a money transfer.
		</p>
	</div>
</div>


<div>
	<h4 class="question">
		Are there types of designs that aren't allowed?
	</h4>

	<div class="answer">
		<p>
			Designs that aren't allowed are the ones that are offensive, contain copyright issues and are sent in damaged files. If your design needs more work, we'll talk you through the process to make it better.
		</p>
	</div>
</div>


<div>
	<h4 class="question">
		How are the submissions chosen?
	</h4>

	<div class="answer">
		<p>
			A jury of artists and creatives sit around a round wooden table, with heaps of coffee, to keep them focused. Each design is then evaluated based on a lot of back-and-forth, and diligence to technique, concept and whether that submission is translatable on a T-shirt.
		</p>
	</div>
</div>

<h2 class="faq_title">User Account Information</h2><hr class="faq_line" />


<div>
	<h4 class="question">
		Do I need an account to place an order?
	</h4> 

	<div class="answer">
		<p>
			Not necessarily. You can create an account (It'll take less than a minute), or you can login through Facebook.
		</p>
</div>
</div>
<h2 class="faq_title">Product & Sizing Information</h2><hr class="faq_line" />

<div>
	<h4 class="question">
		How do I find my size?
	</h4>

	<div class="answer">
		<p>
			You can find your size on our size chart provided on the order form.
		</p>
	</div>
</div>

<div>
	<h4 class="question">
		What are the washing instructions?
	</h4>

	<div class="answer">
	The instructions are as follows:<br/>
	Machine wash cold. Wash dark colors separately. Use non-chlorine bleach only if needed. Tumble dry low. Do not iron awesome design.<br/>
	The tees are primarily 100% organic cotton t-shirts so expect some shrinkage. To lessen this, try hang drying your tees.
	</div>
</div>

<h2 id="sec3" class="faq_title">Shipping & Delivery Information</h2><hr class="faq_line" />


<div>
	<h4 class="question">
		Who will deliver my order? 
	</h4>

	<div class="answer">
		<p>
			We rely on our shipping partner Aramex who has the long-haul capabilities as well as the distribution expertise to get your order to your doorstep as soon as possible.
		</p>
	</div>
</div>

<div>
	<h4 class="question">
		How much is shipping?
	</h4>

	<div class="answer">
		<p>
			The cost of shipping depends on where you live. To find out how much shipping is, add the item to your shopping cart and proceed to checkout.
		</p>
	</div>
</div>

<div>
	<h4 class="question">
		How long do I have to wait to get my T-shirt delivered?
	</h4>

	<div class="answer">
		<p>
			We will start shipping your order one week after the competition has ended. It will then take 1-5 business days to arrive to your home.
		</p>
	</div>
</div>

<div>
	<h4 class="question">
		What can  do if I haven't received my order yet?
	</h4>

	<div class="answer">
		Each order will have a tracking code on it, which you will receive in your confirmation email. If you haven't received your order, you can email this code to <a href="mailto:info@ikimuk.com" target="_blank">info@ikimuk.com</a> and we will track your order for you.
	</div>
</div>

<h2 class="faq_title">Returns</h2><hr class="faq_line" />

	<div>
		<h4 class="question">
			What is your refund policy?
		</h4>

		<div class="answer">
			<p>
				We currently don't give refunds, but we're pretty sure you won't want to return these awesome tees.
			</p>
	</div>
</div>
</div>
</div>
<script type="text/javascript">
								    $(".question").click(function(){
									var sibling = $(this).siblings();
									if(sibling.is(":visible") == false){
									  $(".answer").hide();
									  $(".question").css("font-weight", "normal");
									  $(this).css("font-weight", "bold");
									  sibling.show();
									}
								      });
</script>
<?
include $_SERVER["DOCUMENT_ROOT"].'/block/footer.php';
?>