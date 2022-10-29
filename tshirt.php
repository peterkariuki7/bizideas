<?php

//Tshirt Printing



include('header.php');

?>
		      	<div class="card">
		      		<form method="post" action="result.php">
			      		<div class="card-header text-primary"><h3><b>Tshirt Printing Business</b></h3></div>
			      		<div class="card-body">
		      				<div class="table-responsive">
		      					<table class="table table-striped table-bordered">
		      						<tr>
		      							<th>T-Shirt Printing</th>
		      						</tr>
                                      <tr>
		      							<td>
                                          <img src="img/tshirt.png" width="400" /> 
                                          <h5>Experts You May Need</h5>
                                          <li>A graphic designer - To help you create designs for your tshirts </li>
                                          <li>A webdesigner -To help you build a website</li>
                                          <li>Tshirt printing shop -To print you tshirts on demand.</li>
                                          <div class="col-md-7 text-right">
                                            <a href="book.php
                                           " class="btn btn-primary btn-bg">Check For Available Experts-></a>
                                            </div>
                                        </td>
                                          <td>
                                          <h3>Tshirt Printing Business Guide</h3>
                                          <h4>Steps to Take:</h4>
                                          <h5>1. Pick a category</h5>
                                           <p> Decide on what your brand will be centered around,
                                             ie what your main topic will be.<br>
                                            Examples of categories:
                                            <li>Traditional Themed TShirts</li>
                                            <li>Tech Themed TShirts</li>
                                            <li>Event Themed TShirts</li>
                                            <li>Church Themed TShirts</li>
                                            <li>Corporate Themed TShirts</li>
                                            <li>Sports Themed TShirts</li>
                                            <li>Trending Quotes Themed TShirts</li>
                                              </p> 
                                            </li>
                                            <h5>2. Register a Business Name</h5>
                                            <p> Here are the official steps to do so:<br>
                                            
                                            <li>1. Reservation of business name</li>
                                            <li>2. After reservation, one proceeds to register the business name. </li>
                                            <li>3. The person(s) registering the business name must specify the
                                                 nature of business in one line e.g Stationary, Food Stuff, Hardware.</li>

                                            <li>4. The application must indicate the physical address of the business
                                                 that is; plot number, Road and Town.</li>
                                            <li>5. Indicate the postal code, address and town to be used by the business..</li>
                                            <li>6. Indicate the proprietor(s) details: the name(s) should be in full as per the 
                                                ID Number and all details must be filled in.</li>
                                            <li>6. Download the system generated form.</li>
                                            <li>7. All partners/proprietors must sign on the downloaded form; kindly number the
                                                 signatures in the order of the names on the form.</li>
                                            <li>8. Registration fees amounting to Kshs. 850.</li>


                                          </p>
                                          <h5>3. What you need to run this business</h5>
                                           <p> These are the necessary parts that build this product.
                                            <li>Blank tshirts</li>
                                            <li>Custom Design </li>
                                            <li>Tshirt printing service provider</li>
                                            All of these parts are usually available as a package deal.Tshirt 
                                            printing shops usually provide it as a package deal, you just provide
                                            your design and they handle the rest.
                                            
                                           </p>
                                           <h5>4. How to sell</h5>
                                           <p> These are the various methods you can sell tshirts:
                                            <li>Use whatsapp business to build a storefront and focus on marketing 
                                                your products to your friends via groups and status views
                                            </li>
                                            <li>Advertise on marketplaces- Sign up to marketplaces such as Jumia,Jiji,
                                                Killimall etc.It is free to signup on these platforms and create a store front
                                            you will only be charged a small percentage of every successful sale you make </li>
                                            <li>Social Media- Create a free page on both Facebook and Instagram and post constantly
                                                to build up a following
                                            </li>
                                            <li>Paid advertising- You can advertise on all social media or on google for as little as a 500sh 
                                                budget.
                                            </li>
                                            <li> Sponsorships - You can pay instagrem pages or influencers to advertise your product
                                                to their audience and hopefully make a sale.
                                            </li>
                                            <li>Build a Website - Get a website built for you or build it yourself using website builders.
                                                This is best for branding so as to target bigger clientele such as cororates.A website acts as 
                                                your salesman 24/7
                                            </li>
                                           </p>
                                          </td>
                                            </div>
                                          </div>
                                        </td>
		      							
		      						</tr>

                                    
		      						
		      					</table>
		      				</div>
		      			</div>
		      		</form>
		      	</div>
		    

<?php

include('footer.php');

?>

<script>

$(document).ready(function(){
	$(document).on('click', '.get_appointment', function(){
		var action = 'check_login';
		var supplier_schedule_id = $(this).data('id');
		$.ajax({
			url:"action.php",
			method:"POST",
			data:{action:action, supplier_schedule_id:supplier_schedule_id},
			success:function(data)
			{
				window.location.href=data;
			}
		})
	});
});

</script>