#!/bin/bash
curl -X POST "http://localhost:8000/api/user" \
     -H "Content-Type: application/json" \
     -d '{
            "employeeId": "43214",
            "employeeName": "Ilyas Andika",
            "employeeGender": "male",
            "employeeBirthDate": 1740101809,
            "employeePhoneNumber": "08123456789",
            "employeeEmail": "andikailyas02@gmail.com",
            "employeeRoleId": "1",
            "employeeDepartmentId": "1",
            "employeeShiftId": "1",
            "employeeWorkLocationId": "1",
            "employeePassword": "password123",
            "employeeWhatsApp": "08123456789",
            "employeeLinkedin": "iaika",
            "employeeTelegram": "08123456789",
            "employeeBiography": "string"
            }'