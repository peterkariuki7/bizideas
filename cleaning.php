<?php

//Cleaning Services



include('header.php');

?>
		      	<div class="card">
		      		<form method="post" action="result.php">
			      		<div class="card-header text-primary"><h3><b>Cleaning Services Business</b></h3></div>
			      		<div class="card-body">
		      				<div class="table-responsive">
		      					<table class="table table-striped table-bordered">
		      						<tr>
		      							<th>Cleaning Services</th>
		      						</tr>
                                      <tr>
		      							<td>
                                          <img src="img/clean.png" width="400" /> 
                                          <h5>Experts You May Need</h5>
                                          <li>A graphic designer - To help you create designs for your social media</li>
                                          <li>A webdesigner -To help you build a website</li>
                                          
                                          <div class="col-md-7 text-right">
                                            <a href="book.php
                                           " class="btn btn-primary btn-bg">Check For Available Experts-></a>
                                            </div>
                                        </td>
                                          <td>
                                          <h3>Cleaning Services Business Guide</h3>
                                          <h4>Steps to Take:</h4>
                                          <h5>1. Pick a category</h5>
                                           <p> Decide on what your services will be centered around,
                                             ie what your services will be focused on.<br>
                                            Examples of categories:
                                            <li>Office Cleaning</li>
                                            <li>Sofa Cleaning</li>
                                            <li>Carpet Cleaning</li>
                                            <li>Housekeeping Cleaning</li>
                                            <li>After Event Cleaning</li>
                                            <li>Outdoor Cleaning</li>
                                        
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
                                           <p> These are the necessary parts that are needed.
                                            <li>Tools and Equipments</li>
                                            <li>Detergents </li>
                                            <li>Protective Clothing</li>
                                            All of these tools are usually available online or in markets.
                                            The trick is to look for the most affordable products and only buy What
                                            you need.For detergets simply google how to make homemade versiond of the 
                                            particular detergent type, this will cut your costs greatly.
                                            
                                            
                                           </p>
                                           <h5>4. How to sell</h5>
                                           <p> These are the various methods you can sell your cleaning services:
                                            <li>Use whatsapp business to build a storefront and focus on marketing 
                                                your services to your friends via groups and status views
                                            </li>
                                            <li>Advertise on marketplaces- Sign up to marketplaces such as Jiji, where you can 
                                                advertise your services for free and you can present your seervices to a
                                                wider audience.It is free to signup on these platforms and create a store front
                                            you will only be charged a small percentage of every successful sale you make </li>
                                            <li>Social Media- Create a free page on both Facebook and Instagram and post constantly
                                                to build up a following
                                            </li>
                                            <li>Paid advertising- You can advertise on all social media or on google for as little as a 500sh 
                                                budget.
                                            </li>
                                            <li> Sponsorships - You can pay instagrem pages or influencers to advertise your servises
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