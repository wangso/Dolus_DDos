import sys, logging
import frenetic
import pymysql
from frenetic.syntax import *
from python.stats.network_information_base import *
from tornado.ioloop import PeriodicCallback, IOLoop
from functools import partial

from python.settings import Session

# hostname = 'localhost'
# username = 'mtd'
# password = 'mtd'
# database = 'mtd'
# myConnection = pymysql.connect( host=hostname, user=username, passwd=password, db=database )

def doUpdate( switch, data ) :
    session = Session()
    query =f"""INSERT INTO mtd.logs "
                "(switch_id, port_id, timestamp, rx_packets, tx_packets, rx_bytes, tx_bytes, rx_dropped, tx_dropped, rx_errors, tx_errors, rx_fram_err, rx_over_err, rx_crc_err, collisions, delta_rx_packets, delta_tx_packets, delta_rx_bytes, delta_tx_bytes) "
                "VALUES({switch}, {data['port_no']}, NOW(), {data['rx_packets']}, {data['tx_packets']}, {data['rx_bytes']}, {data['tx_bytes']}, {data['rx_dropped']}, {data['tx_dropped']}, {data['rx_errors']}, {data['tx_errors']}, {data['rx_fram_err']}, {data['rx_over_err']}, {data['rx_crc_err']}, {data['collisions']}, "
                "{data['rx_packets']}-(select rx_packets from mtd.logs a2 where a2.switch_id = {switch} and a2.port_id = {data['port_no']} and a2.timestamp = (select max(timestamp) from mtd.logs a22 "
                "where a22.switch_id = a2.switch_id and a22.port_id = a2.port_id and a22.timestamp < NOW())),"
                "{data['tx_packets']}-(select tx_packets from mtd.logs a2 where a2.switch_id = {switch} and a2.port_id = {data['port_no']} and a2.timestamp = (select max(timestamp) from mtd.logs a22 "
                "where a22.switch_id = a2.switch_id and a22.port_id = a2.port_id and a22.timestamp < NOW())),"
                "{data['rx_bytes']}-(select rx_bytes from mtd.logs a2 where a2.switch_id = {switch} and a2.port_id = {data['port_no']} and a2.timestamp = (select max(timestamp) from mtd.logs a22 "
                "where a22.switch_id = a2.switch_id and a22.port_id = a2.port_id and a22.timestamp < NOW())),"
                "{data['tx_bytes']}-(select tx_bytes from mtd.logs a2 where a2.switch_id = {switch} and a2.port_id = {data['port_no']} and a2.timestamp = (select max(timestamp) from mtd.logs a22 "
                "where a22.switch_id = a2.switch_id and a22.port_id = a2.port_id and a22.timestamp < NOW()))"
                "); """

    print(query)
    result = session.execute(query)
    session.commit()

class StatsApp1(frenetic.App):

  client_id = "stats"

  def __init__(self):
    frenetic.App.__init__(self)
    self.nib = NetworkInformationBase(logging)

  def connected(self):
    def handle_current_switches(switches):
      logging.info("Connected to Frenetic - Stats for switch: " + str(switches.keys()[1]))
      dpid = switches.keys()[1]
      self.nib.set_dpid(dpid)
      self.nib.set_ports( switches[dpid] )
      PeriodicCallback(self.count_ports, 2000).start()
    self.current_switches(callback=handle_current_switches)

  def print_count(self, future, switch):
    data = future.result()
    doUpdate(switch, data)
#    myConnection.close()

  def count_ports(self):
    switch_id = self.nib.get_dpid()
#    print self.nib.all_ports()
    for port in self.nib.all_ports():
      ftr = self.port_stats(switch_id, str(port))
      f = partial(self.print_count, switch = switch_id)
      IOLoop.instance().add_future(ftr, f)


if __name__ == '__main__':

  app = StatsApp1()
  app.start_event_loop()