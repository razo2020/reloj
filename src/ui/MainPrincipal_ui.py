# -*- coding: utf-8 -*-

################################################################################
## Form generated from reading UI file 'MainPrincipal.ui'
##
## Created by: Qt User Interface Compiler version 6.6.3
##
## WARNING! All changes made in this file will be lost when recompiling UI file!
################################################################################

from PySide6.QtCore import (QCoreApplication, QDate, QDateTime, QLocale,
    QMetaObject, QObject, QPoint, QRect,
    QSize, QTime, QUrl, Qt)
from PySide6.QtGui import (QBrush, QColor, QConicalGradient, QCursor,
    QFont, QFontDatabase, QGradient, QIcon,
    QImage, QKeySequence, QLinearGradient, QPainter,
    QPalette, QPixmap, QRadialGradient, QTransform)
from PySide6.QtWidgets import (QApplication, QFrame, QSizePolicy, QVBoxLayout,
    QWidget)

class Ui_MainPrincipal(object):
    def setupUi(self, MainPrincipal):
        if not MainPrincipal.objectName():
            MainPrincipal.setObjectName(u"MainPrincipal")
        MainPrincipal.resize(1024, 462)
        self.verticalLayout = QVBoxLayout(MainPrincipal)
        self.verticalLayout.setSpacing(0)
        self.verticalLayout.setObjectName(u"verticalLayout")
        self.verticalLayout.setContentsMargins(0, 0, 0, 0)
        self.fr_central = QFrame(MainPrincipal)
        self.fr_central.setObjectName(u"fr_central")
        self.fr_central.setFrameShape(QFrame.StyledPanel)
        self.fr_central.setFrameShadow(QFrame.Raised)

        self.verticalLayout.addWidget(self.fr_central)


        self.retranslateUi(MainPrincipal)

        QMetaObject.connectSlotsByName(MainPrincipal)
    # setupUi

    def retranslateUi(self, MainPrincipal):
        MainPrincipal.setWindowTitle(QCoreApplication.translate("MainPrincipal", u"Form", None))
    # retranslateUi

