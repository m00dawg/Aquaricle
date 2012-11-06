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
                 DATEDIFF(NOW(), testedOn) AS daysSinceChange
                FROM WaterLogs 
                WHERE amountExchanged IS NOT NULL
                AND aquariumID = %s 
                ORDER BY testedOn DESC LIMIT 1""",
            [aquarium_id])[0]