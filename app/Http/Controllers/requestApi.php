<?php

//customer login api

/*
@inputs {
    email : '',
    password : '',
    app : 'android/web'
}
@POST
@URL => api/cusLogin

@response shape
{
    "status": "Login failed Email/Password Not Correct.",
    "stype": "danger",
    "data": []
}
data : {id: 1, first: Suhail, last: Tameem, full: Suhail Abdalrhman Ahmed Adam, email: suhail@tameem.sd, phone: 249900550355, phone2: null, country: 1, address: null, type: null, ws: 1, last_login: null, use: null, created_at: 2022-04-26T21:15:23.000000Z, updated_at: 2022-04-28T21:00:30.000000Z}



//new shipping request
/*
@inputs
cuid  //customer or user id
sender //name
senderPhone
senderLoc

receiver
receiverPhone
receiverPhone2
receiverAddr
shippType // 1-air 2-sea 3-land
containerType

fromCountry
toCountry
serviceType

totalWeight
totalPrices

note
wsid
step


*/

/*
Lists

#getways
1- admin
2- user
3- other

#getwayType
1-web
2-mobile
3-other

#requestStatus  id=> 2
1- 4 Waiting acceptance
2- 5 Accepted
3- 6 Rejected
4- 7 Postponed

#ShippingType id=> 1
1- Air
2- Sea
3- Land

#ContainerType id=> 3
1- container
2- pallet
3- refrigerator
4- box or package
5- Other

#servicesType  id=> 4
1- shipping + custome clearnce
2- shipping only


#packageType
1- goods
2- clothes and shoes
3- Cosmetics
4- Electrical devices
5- Electronic Devices
6- Furniture
7- Home Supplies
8- Cars
9- Other

@sysList
'name'

@list
'en',
'ar',

*/

?>
