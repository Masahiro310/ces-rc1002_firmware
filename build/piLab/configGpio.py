import RPi.GPIO as GPIO

GPIO.setmode(GPIO.BCM)

GPIO.setup(4,GPIO.IN,pull_up_down=GPIO.PUD_UP)
GPIO.setup(5,GPIO.IN,pull_up_down=GPIO.PUD_UP)
GPIO.setup(6,GPIO.IN,pull_up_down=GPIO.PUD_UP)
#GPIO.setup(7,GPIO.IN,pull_up_down=GPIO.PUD_UP)
#GPIO.setup(8,GPIO.IN,pull_up_down=GPIO.PUD_UP)
#GPIO.setup(9,GPIO.IN,pull_up_down=GPIO.PUD_UP)
#GPIO.setup(10,GPIO.IN,pull_up_down=GPIO.PUD_UP)
#GPIO.setup(11,GPIO.IN,pull_up_down=GPIO.PUD_UP)
GPIO.setup(12,GPIO.IN,pull_up_down=GPIO.PUD_UP)
GPIO.setup(13,GPIO.IN,pull_up_down=GPIO.PUD_UP)


GPIO.setup(16,GPIO.IN,pull_up_down=GPIO.PUD_UP)
GPIO.setup(17,GPIO.IN,pull_up_down=GPIO.PUD_UP)

GPIO.setup(19,GPIO.IN,pull_up_down=GPIO.PUD_UP)
GPIO.setup(20,GPIO.IN,pull_up_down=GPIO.PUD_UP)
GPIO.setup(21,GPIO.IN,pull_up_down=GPIO.PUD_UP)
GPIO.setup(22,GPIO.IN,pull_up_down=GPIO.PUD_UP)
GPIO.setup(23,GPIO.IN,pull_up_down=GPIO.PUD_UP)
GPIO.setup(24,GPIO.IN,pull_up_down=GPIO.PUD_UP)
GPIO.setup(25,GPIO.IN,pull_up_down=GPIO.PUD_UP)
GPIO.setup(26,GPIO.IN,pull_up_down=GPIO.PUD_UP)
GPIO.setup(27,GPIO.IN,pull_up_down=GPIO.PUD_UP)
