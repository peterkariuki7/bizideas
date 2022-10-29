<?php

//Podcast biz



include('header.php');

?>
		      	<div class="card">
		      		<form method="post" action="result.php">
			      		<div class="card-header text-primary"><h3><b>Podcast Business</b></h3></div>
			      		<div class="card-body">
		      				<div class="table-responsive">
		      					<table class="table table-striped table-bordered">
		      						<tr>
		      							<th>Podcast Business</th>
		      						</tr>
                                      <tr>
		      							<td>
                                          <img src="img/pod.png" width="400" /> 
                                          <h5>Experts You May Need</h5>
                                          <li>A graphic designer - To help you create designs for your social media</li>
                                          <li>A webdesigner -To help you build a website</li>
                                          <li>A digital marketer - to hel you advertise online</li>
                                          
                                          <div class="col-md-7 text-right">
                                            <a href="book.php
                                           " class="btn btn-primary btn-bg">Check For Available Experts-></a>
                                            </div>
                                        </td>
                                          <td>
                                          <h3>Podcast Business Guide</h3>
                                          <h4>Steps to Take:</h4>
                                          <h5>1. Pick a topic</h5>
                                           <p> Decide on what your podcast will be focused on.<br>
                                            Examples of topics:
                                            <li>Business and entrepreneurshipw/li>
                                            <li>Comedy</li>
                                            <li>Story telling</li>
                                            <li>Guides and Tutorials</li>
                                            <li>Audiobooks</li>
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
                                            <li>Smart phone</li>
                                            <li>Microphone </li>
                                            <li>Cover art</li></p>
                                            <li>Teaser</li>
                                            <li>Premade Intro music </li>
                                            <li>Welcome message</li>
                                            <li>Advertisement spot</li>
                                            <li>Main Message</li>
                                            <li>Call to action</li>
                                            <li>Premade outro music</li>
                                            </p>
                                            <p>Podcast make money by:
                                            <li>Having sponsors</li>
                                            <li>Advertising products and affiliate offers</li>
                                            <li>Repurposing the content into other platforms</li>
                                            <li>Leveraging the podcast to bring sales to your other related businesses</li>
                                           </p>

                                           <h5>4. How to advertise your podcast</h5>
                                           <p> These are the various methods you can sell your Podcast Business:
                                            <li>Use whatsapp to share your podcast to your friends via groups and status views
                                            </li>
                                            <li>Advertise on directories- Sign up to directories where you can 
                                                advertise your podcast for free and you can presentit to a
                                                wider audience. </li>
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
                                            <li>Repurpose your content by clipping it into smaller chunks and sharing it
                                                onto platforms like Tiktok</li>
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