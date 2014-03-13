from django.db import models
from django.db import connection

'''
This might be better added to the model?
'''
class WaterLogStatsManager(models.Manager):

    def get_last_water_change(self, aquarium_id):
        ''' 
            Get the last water change made and how many days it has been 
            Returns a single object.
        '''
        return self.raw(
            """SELECT waterLogID,
                DATEDIFF(NOW(), testedOn) AS daysSinceChange,
                ROUND((amountExchanged / capacity) * 100, 0) AS exchangedPercentage
                FROM WaterLogs 
                JOIN Aquariums ON Aquariums.aquariumID = WaterLogs.aquariumID
                WHERE amountExchanged IS NOT NULL
                AND WaterLogs.aquariumID = %s
                ORDER BY testedOn DESC LIMIT 1""",
            [aquarium_id])

    def get_water_logs(self, aquarium_id, num_items):
        '''
            Get the latest water logs with some calculated items
        '''
        return self.raw(
            """SELECT waterLogID,
                ROUND((amountExchanged / capacity) * 100, 0) AS exchangedPercentage
                FROM WaterLogs 
                JOIN Aquariums ON Aquariums.aquariumID = WaterLogs.aquariumID
                AND WaterLogs.aquariumID = %s
                ORDER BY testedOn DESC LIMIT %s  
            """,
            [aquarium_id, num_items])
    